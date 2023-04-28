<?php
/**
 * Class Torrent
 *
 * This class is for uploading, editing and retrieving new torrents.
 *
 * Depends on: Medoo, Bencode, Config
 *
 * Written by Jack Ryan Davies (jrd15)
 */

namespace Torrent;

// Ensure this class is loaded and don't depend on other scripts
// to include it for us.
require_once("class_bencode.php");
require_once("class_config.php");
require_once("utility_functions.php");

use Exception;
use Medoo\Medoo;
use Bencode\Bencode;
use Config\Config;

class Torrent extends Config
{
    protected $torrentCache = array(),
              $peerExpirationTime;

    function __construct(
        Medoo &$db
    ) {
        // PHP equivalent of "super".
        parent::__construct(db: $db);

        // If the peer hasn't updated its status within 30 seconds of the announcement interval, deem it dead.
        $this->peerExpirationTime = time() - (intval(parent::getConfigVal("announcement_interval")) + 30);
    }

    /**
     * Saves a torrent to a users bookmarks.
     * @param string $torrentIdLong The long ID of the torrent to add.
     * @param int $torrentId The short (int) ID of the torrent to add.
     * @param string $infoHash The info_hash of the torrent to add.
     * @param int $userID The ID of the account to add the torrent to.
     * @param bool $delete True = Add, False = Delete
     * @return void
     * @throws Exception
     */
    public function addBookmark(
        string $torrentIdLong = "",
        int    $torrentId = 0,
        string $infoHash = "",
        int    $userID = 0,
        bool   $delete = false
    ): void {
        if (!$userID > 0) {
            throw new Exception("No user ID provided!");
        }

        if (!empty($torrent = $this->getTorrent(
            torrentIdLong: $torrentIdLong,
            torrentId: $torrentId,
            infoHash: $infoHash
        ))) {
            // Torrent exists.
            if ($torrent['uploader']['uid'] == $userID) {
                throw new Exception("Uploaders cannot bookmark their own content!");
            }

            if ($delete) {
                $this->db->delete("bookmarks",
                    [
                        "uid"         =>  $userID,
                        "torrent_id"  =>  $torrent['torrent_id']
                    ]
                );

                return;
            }

            // Has the bookmark been added already?
            if (!$this->db->get("bookmarks",
                [
                    "torrent_id"
                ],
                [
                    "uid" => $userID
                ]
            )) {
                // Bookmark has not been added before.
                $this->db->insert("bookmarks",
                    [
                        "torrent_id"  =>  $torrent['torrent_id'],
                        "uid"         =>  $userID
                    ]
                );
            }
        }
    }

    /**
     * Takes the raw files array from a decoded torrent and converts it into a tree of files and directories.
     * @param $array The array of the decoded torrent file "files" key.
     * @return array|mixed
     */
    public function torrentFilesToArray($array) {
        $paths = array();

        // Create an array containing the full path of each file.
        foreach($array as $file) {
            if (isset($file['path'])) {
                $depth  =  count($file['path']);
                $path   = "";

                for ($i = 0; $i < $depth; $i++) {
                    $path .= "/".$file['path'][$i];
                }

                $paths[] = $path;
            }
        }

        // Create a tree based on full paths.
        // The following code was taken from Stackoverflow. It takes an array of paths and converts them into
        // an array.
        // Source: https://stackoverflow.com/a/23890006
        $result = array();

        foreach ($paths AS $path) {
            $prev = &$result;

            $s = strtok($path, '/');

            while (($next = strtok('/')) !== false) {
                if (!isset($prev[$s])) {
                    $prev[$s] = array();
                }

                $prev = &$prev[$s];
                $s = $next;
            }

            $prev[] = $s;

            unset($prev);
        }

        return $result;
    }

    /**
     * Adds a new torrent to the database.
     * @param string $title The title of the file.
     * @param string $description The description of the file.
     * @param int $categoryIndex The category ID in which to store the file.
     * @param string|null $coverImagePath The cover image for the file (optional)
     * @param string $torrentFilePath Full path to the .torrent file.
     * @param int $userId The ID (short) of the account to associate the new torrent to.
     * @param int $isAnonymous 1 = Mark the torrent as anonymous.
     * @return array An array of the newly added torrent information.
     * @throws Exception Exception if a mandatory field is missing or invalid file provided.
     */
    public function addTorrent(
        string $title,
        string $description,
        int $categoryIndex,
        string $coverImagePath = null,
        string $torrentFilePath,
        int $userId,
        int $isAnonymous = 0
    ): array {
        $fileTree = "";

        if (!file_exists($torrentFilePath)) {
            throw new Exception("Torrent file does not exist! Cannot import!");
        }

        if (!$decoded = Bencode::decode(file_get_contents($torrentFilePath))) {
            throw new Exception("Failed to parse torrent file!");
        }

        // A torrent's info hash is calculated via a sha1 of the bencoded "info" array.
        $infoHash  =  sha1(Bencode::encode($decoded['info']));

        if (!empty($this->getTorrent(infoHash: $infoHash))) {
            throw new Exception("Provided torrent already exists!");
        }

        if (!isset($decoded['info']['name'])) {
            // "name" doesn't exist in the "info" array, meaning
            // that we are expecting a multi-file torrent.

            if (!isset($decoded['info']['files']) || count($decoded['info']['files']) <= 0) {
                throw new Exception("Torrent contains no files!");
            }
        }

        if (!isset($decoded['info']['files'])) {
            // We'll typically see this for torrents with only a single file.
            $fileSize     =  $decoded['info']['length'];
            $fileSizeCalc =  bytesFormat($decoded['info']['length']);
            $fileTree = [ $decoded['info']['name'] ];
        } else {
            // Torrent has more than one file. So we'll need to get the size of each file, sum them
            // and then calculate the overall size.
            if (isset($decoded['info']['files'])) {

                $fileTree = $this->torrentFilesToArray($decoded['info']['files']);
                $fileSize = 0;

                foreach ($decoded['info']['files'] as $file) {
                    if (!isset($file['length'])) {
                        throw new Exception("Field 'length' is not set for a file!");
                    }

                    $fileSize += $file['length'];
                }

                $fileSizeCalc = bytesFormat($fileSize);
            } else {
                throw new Exception("Torrent contains no files!");
            }
        }

        if (!isset($decoded['info']['private']) || $decoded['info']['private'] == 0) {
            // Whilst we can mark the torrent as private via PHP, it's best for the uploader to mark it as
            // private as it means the info_hash won't change and they will be instantly seeding content
            // rather than needing to download an updated .torrent from the site.
            throw new Exception(("The torrent must be marked as private! Regenerate it."));
        }

        if (isset($decoded['announce-list'])) {
            // Torrent contains a list of backup tracker URL. We don't need these, so remove them.
            unset($decoded['announce-list']);
        }

        if (isset($decoded['announce'])) {
            // Remove the announcement URL currently set in the torrent. This will be added/updated each
            // time a user downloads the .torrent from the site. No need to waste additional database storage
            // on URLs that may change.
            unset($decoded['announce']);
        }

        if (empty($title = trim($title))) {
            throw new Exception("Title cannot be empty!");
        }

        if (empty($description = trim($description))) {
            throw new Exception("Description cannot be empty!");
        }

        if ($isAnonymous > 0 ) {
            $isAnonymous = 1;
        } else {
            $isAnonymous = 0;
        }

        if ($categoryIndex <= 0) {
            throw new Exception("No category index specified!");
        }

        if (!parent::doesTorrentCategoryExist(categoryIndex: $categoryIndex)) {
            throw new Exception("Category does not exist!");
        }

        $toInsert = array(
            "uid"             =>  $userId,
            "anonymous"       =>  $isAnonymous,
            "category_index"  =>  $categoryIndex,
            "info_hash"       =>  $infoHash,
            "torrent_id_long" =>  Medoo::raw("UUID()"),
            "file_name"       =>  "$title",
            "file_size"       =>  $fileSize,
            "file_size_calc"  =>  $fileSizeCalc,
            "title"           =>  "$title",
            "description"     =>  "$description",
            "upload_time"     =>  time(),
            "published"       =>  1,
            "torrent_data"    =>  Bencode::encode($decoded),
            "torrent_tree"    =>  json_encode($fileTree, JSON_PRETTY_PRINT)
        );

       if (!empty($coverImagePath = trim($coverImagePath))) {
           if (!file_exists($coverImagePath)) {
               throw new Exception("Cover image cannot be found!");
           }

           if(!is_array(getimagesize($coverImagePath))){
               throw new Exception("Invalid cover image!");
           }

           $toInsert['cover'] = base64_encode(file_get_contents($coverImagePath));
       }

       if (!$this->db->insert("torrents", $toInsert)) {
           throw new Exception("Failed to insert torrent into database!");
       }

       return $this->getTorrent(torrentId: $this->db->id());
    }

    /**
     * Removes a torrent from the database.
     * @param string $torrentIdLong The long ID of the torrent.
     * @param int $torrentId The short (int) ID of the torrent.
     * @param string $infoHash The info hash of the torrent.
     * @return true True on successful deletion.
     * @throws Exception Exception when the torrent does not exist or internal database error.
     */
    public function deleteTorrent(
        string $torrentIdLong = "",
        int    $torrentId = 0,
        string $infoHash = "",
    ) {
        $torrent = $this->getTorrent(
            torrentIdLong: $torrentIdLong,
            torrentId: $torrentId,
            infoHash: $infoHash
        );

        if (isset($torrent['torrent_id']) && $torrent['torrent_id'] > 0) {
            $this->db->delete("torrents",
                [
                    "torrent_id"  =>  $torrent['torrent_id']
                ]
            );

            return true;
        }

        throw new Exception("Torrent not found!");
    }

    /**
     * Retrieves information relating to a torrent.
     * @param string $torrentIdLong The long ID of a torrent.
     * @param int $torrentId The short (int) ID of a torrent.
     * @param string $infoHash The info hash of a torrent.
     * @param bool $download True = Return bencoded torrent data for download.
     * @param string $peerId The PID of the account trying to initiate a download.
     * @param int $userId The short (int) ID of a user account.
     * @return false|mixed|string|null
     * @throws Exception Exception when a torrent or user account does not exist, or internal database error.
     */
    public function getTorrent(
        string $torrentIdLong = "",
        int    $torrentId = 0,
        string $infoHash = "",
        bool   $download = false,
        string $peerId = "",
        int    $userId = 0
    ) {
        $where = array();

        if (!empty($torrentIdLong = trim($torrentIdLong))) {
            $where = array(
                "torrent_id_long" => $torrentIdLong
            );

            // We use this "identifier" to name the cache variable for this request.
            $identifier = "torrent_id_long".$torrentIdLong;
        }

        if ($torrentId > 0) {
            $where = array(
                "torrents.torrent_id" => $torrentId
            );

            $identifier = "torrent_id".$torrentId;
        }

        if (!empty($infoHash = trim($infoHash))) {
            $where = array(
                "info_hash" => $infoHash
            );

            $identifier = "info_hash".$infoHash;
        }

        if (!$where) {
            throw new Exception("No torrent GET parameters specified!");
        }

        $where['published']  =  1;
        $where['GROUP']      =  "torrent_id";

        // No database cache is set. Make database request and cache it.
        if (!isset($this->torrentCache[$identifier])) {
            $this->torrentCache[$identifier] = $this->db->get("torrents",
                [
                    "[<]users"      => "uid", // We're joining the users table based on the uid.
                    "[<]categories" => "category_index",
                    "[>]groups"     => "gid",
                    "[>]peers"      => array("torrents.torrent_id" => "torrent_id")
                ],
                [
                    "uploader" => [
                        "users.username(username)",
                        "users.uid_long(uuid)",
                        "users.uid",
                        "groups.group_name"
                    ],
                    "torrents.torrent_id",
                    "torrents.torrent_id_long(torrent_uuid)",
                    "torrents.anonymous",
                    "torrents.category_index",
                    "torrents.info_hash",
                    "torrents.file_name",
                    "torrents.file_size",
                    "torrents.file_size_calc",
                    "torrents.title",
                    "torrents.description",
                    "torrents.cover",
                    "torrents.upload_time",
                    "torrents.published",
                    "torrents.staff_recommended",
                    "torrents.torrent_data",
                    "torrents.torrent_tree",
                    "torrents.category_index",
                    "categories.category_subof",
                    "categories.category_name",
                    "seeders"  => Medoo::raw("SUM(if (peers.seeding = 1 and peers.last_seen > ".$this->peerExpirationTime.", 1, 0))"),
                    "leechers" => Medoo::raw("SUM(if (peers.seeding = 0 and peers.last_seen > ".$this->peerExpirationTime.", 1, 0))")
                ],
                $where
            );

            if (!empty($this->torrentCache[$identifier])) {
                $this->torrentCache[$identifier]['peers'] = $this->torrentCache[$identifier]['seeders'] +
                                                            $this->torrentCache[$identifier]['leechers'];

                $this->torrentCache[$identifier]['upload_time'] = timeAgo(timestamp: $this->torrentCache[$identifier]['upload_time']);

                if ($download) {
                    // We need to prepare a .torrent for download.

                    if (empty($peerId = trim($peerId))) {
                        throw new Exception("No peer ID provided for torrent download!");
                    }

                    if ($userId <= 0) {
                        throw new Exception("No user ID provided for torrent download!");
                    }

                    if (empty($this->torrentCache[$identifier])) {
                        throw new Exception("Torrent cannot be downloaded because it does not exist.");
                    }

                    // Now we need to decode the torrent blob and add our tracker URLs.
                    if (!$decoded = Bencode::decode($this->torrentCache[$identifier]['torrent_data'])) {
                        throw new Exception("Failed to parse torrent file!");
                    }

                    // Add our tracker announcement URL with the users peer Id appended to it.
                    $decoded['announce']      =  parent::getAnnouncementUrl(peerId: $peerId);

                    // Add our backup tracker URL, if any.
                    $decoded['announce-list'] =  array(parent::getBackupAnnouncementUrl(peerId: $peerId));

                    // Insert the user download history into the "downloads" table.
                    if (empty($this->db->get("downloads",
                        [
                            "torrent_id"
                        ],
                        [
                            "uid" => $userId
                        ]
                    ))) {
                        // User hasn't downloaded this torrent before.
                        $this->db->insert("downloads",
                            [
                                "torrent_id" => $this->torrentCache[$identifier]['torrent_id'],
                                "uid"        => $userId
                            ]
                        );
                    }

                    // Return the customised bencoded torrent.
                    return Bencode::encode($decoded);
                }
            } else {
                if ($download) {
                    throw new Exception("Torrent does not exist!");
                }
            }
        }


        // Return the cached database response.
        return $this->torrentCache[$identifier];
    }

    /**
     * Retrieves a list of torrents based on the search parameters.
     * @param string $searchQuery Strings to search for in the query.
     * @param string $searchBy Search by data, size, id or title.
     * @param int $maxResults Maximum number of results to return.
     * @param string $sortBy Sort by time, size, title or ID.
     * @param bool $orderDesc True = Order descending.
     * @param array $categories Array of category ID's to filter by.
     * @param bool $getUploadHistory True = Get upload history of a user (requires getShareUserId)
     * @param bool $getDownloadHistory True = Get download history of a user (requires getShareUserId)
     * @param int $getShareUserId The ID of the account to retrieve download/upload history for.
     * @param int $getBookmarksUserId The ID of the account to retrieve bookmarks for.
     * @return mixed
     * @throws Exception
     */
    public function getTorrentListing(
        string $searchQuery =  "",
        string $searchBy    =  "title",
        int    $maxResults  =  50,
        string $sortBy      =  "torrent_id",
        bool   $orderDesc   =  false,
        array  $categories  = array(),
        bool   $getUploadHistory = false,
        bool   $getDownloadHistory = false,
        int    $getShareUserId = 0,
        int    $getBookmarksUserId = 0
    ) {
        $order = "ASC";
        $table = "torrents";
        $join  = array(
            "[<]categories" => "category_index",
            "[<]users"      => "uid",
            "[>]groups"     => "gid",
            "[>]peers"      => array("torrents.torrent_id" => "torrent_id")
        );

        if ($orderDesc) {
            $order = "DESC";
        }

        $where = array(
            "published" => 1,
            "GROUP"     => "torrent_id"
        );

        if ($maxResults <= 0 || $maxResults > 100) {
            throw new Exception("Invalid return limit!");
        }

        if ($getUploadHistory && $getShareUserId > 0) {
            $searchQuery = "upload-history$getShareUserId";
        }

        if ($getDownloadHistory && $getShareUserId > 0) {
            $searchQuery = "download-history$getShareUserId";
        }

        if ($getBookmarksUserId > 0) {
            $searchQuery = "bookmarks$getBookmarksUserId";
        }

        // We have this query already cached. It is unlikely that we will have the
        // same query with different filter parameters in the same request, so this will
        // suffice.
        if (isset($this->torrentCache['torrent_listing'][$searchQuery])) {
            return $this->torrentCache['torrent_listing'][$searchQuery];
        }

        switch($sortBy) {
            case "time":
                $sortBy = "time";
                break;
            case "size":
                $sortBy = "file_size";
                break;
            case "title":
                $sortBy = "title";
            case "id":
            default:
                $sortBy = "torrent_id";
                break;
        }

        $where["ORDER"] = array(
            $sortBy => $order
        );

        switch($searchBy) {
            case "date":
                $searchBy = "time";
                break;
            case "size":
                $searchBy = "file_size";
                break;
            case "id":
                $searchBy = "torrent_id";
                break;
            default:
                $searchBy = "title";
                break;
        }

        if (!empty($searchQuery = trim($searchQuery))) {
            $where[$searchBy."[~]"] = "$searchQuery";
        }

        if (count($categories) > 0) {
            $where["AND"]["categories.category_index"] = array();

            foreach ($categories as $category) {
                $where["AND"]["categories.category_index"][] = $category;
            }
        }

        if ($getUploadHistory && $getShareUserId > 0) {
            // We're retrieving a users upload history.
            $where['torrents.uid'] = $getShareUserId;
            unset($where[$searchBy."[~]"]);
        }

        if ($getDownloadHistory && $getShareUserId > 0) {
            $table = "downloads";
            $where['downloads.uid']    =  $getShareUserId;
            $where['torrents.uid[!]']  =  $getShareUserId;
            $join = array(
                "[<]torrents"    =>  "torrent_id",
                "[>]peers"       =>  array("torrents.torrent_id" => "torrent_id"),
                "[<]users"       =>  array("torrents.uid" => "uid"),
                "[>]groups"      =>  array("users.gid" => "gid"),
                "[<]categories"  =>  array("torrents.category_index" => "category_index")
            );
            unset($where[$searchBy."[~]"]);
        }

        if ($getBookmarksUserId > 0) {
            $table = "bookmarks";
            $where['bookmarks.uid']    =  $getBookmarksUserId;
            $where['torrents.uid[!]']  =  $getBookmarksUserId;
            $join = array(
                "[<]torrents"    =>  "torrent_id",
                "[>]peers"       =>  array("torrents.torrent_id" => "torrent_id"),
                "[<]users"       =>  array("torrents.uid" => "uid"),
                "[>]groups"      =>  array("users.gid" => "gid"),
                "[<]categories"  =>  array("torrents.category_index" => "category_index")
            );
            unset($where[$searchBy."[~]"]);
        }

        // Save the query response to the cache, regardless of what the response is.
        $this->torrentCache['torrent_listing'][$searchQuery] = $this->db->select($table, $join,
            [
                "uploader" => [
                    "users.username(username)",
                    "users.uid_long(uuid)",
                    "groups.group_name",
                    "users.gid(group_id)"
                ],
                "torrents.torrent_id",
                "torrents.torrent_id_long(torrent_uuid)",
                "torrents.title",
                "torrents.info_hash",
                "torrents.anonymous",
                "torrents.upload_time",
                "torrents.file_size_calc",
                "categories.category_name",
                "seeders"  => Medoo::raw("SUM(if (peers.seeding = 1 and peers.last_seen > ".$this->peerExpirationTime.", 1, 0))"),
                "leechers" => Medoo::raw("SUM(if (peers.seeding = 0 and peers.last_seen > ".$this->peerExpirationTime.", 1, 0))")
            ],
            $where
        );

        foreach ($this->torrentCache['torrent_listing'][$searchQuery] as &$torrent) {
            $torrent['upload_time'] = timeAgo(timestamp: $torrent['upload_time']);
        }

        return $this->torrentCache['torrent_listing'][$searchQuery];
    }
}