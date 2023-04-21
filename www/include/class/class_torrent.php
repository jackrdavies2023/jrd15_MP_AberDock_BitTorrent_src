<?php
/**
 * Class Torrent
 *
 * This class is for uploading, editing and retrieving new torrents.
 *
 * Written by Jack Ryan Davies (jrd15)
 */

namespace Torrent;

// Ensure this class is loaded and don't depend on other scripts
// to include it for us.
require_once("class_bencode.php");

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

    public function addTorrent(
        string $title,
        string $description,
        int $categoryIndex,
        string $coverImagePath = null,
        string $torrentFilePath,
        int $userId,
        int $isAnonymous = 0
    ): array {
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

        if (isset($decoded['info']['piece length'])) {
            // We'll typically see this for torrents with only a single file.
            $fileSize     =  $decoded['info']['piece length'];
            $fileSizeCalc =  $this->bytesFormat($decoded['info']['piece length']);
        } else {
            // Torrent has more than one file. So we'll need to get the size of each file, sum them
            // and then calculate the overall size.
            if (isset($decoded['info']['files'])) {
                $fileSize = 0;

                foreach ($decoded['info']['files'] as $file) {
                    if (!isset($file['piece length'])) {
                        throw new Exception("Field 'piece length' is not set for a file!");
                    }

                    $fileSize += $file['piece length'];
                }

                $fileSizeCalc = $this->bytesFormat($fileSize);
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
            "torrent_data"    =>  Bencode::encode($decoded)
        );

       if (!empty($coverImagePath = trim($coverImagePath))) {
           if (!file_exists($coverImagePath)) {
               throw new Exception("Cover image cannot be found!");
           }

           $toInsert['cover'] = base64_encode(file_get_contents($coverImagePath));
       }

       if (!$this->db->insert("torrents", $toInsert)) {
           throw new Exception("Failed to insert torrent into database!");
       }

       return $this->getTorrent(torrentId: $this->db->id());
    }

    public function getTorrent(
        string $torrentIdLong = "",
        int    $torrentId = 0,
        string $infoHash = "",
        bool   $download = false,
        string $peerId = ""
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
            }
        }

        if ($download) {
            // We need to prepare a .torrent for download.

            if (empty($peerId = trim($peerId))) {
                throw new Exception("No peer ID provided for torrent download!");
            }

            if (empty($this->torrentCache[$identifier])) {
                throw new Exception("Torrent cannot be downloaded because it does not exist.");
            }

            // Now we need to decode the torrent blob and add our tracker URLs.
            if (!$decoded = Bencode::decode($this->torrentCache[$identifier]['torrent_data'])) {
                throw new Exception("Failed to parse torrent file!");
            }

            // Add our tracker announcement URL with the users peer Id appended to it.
            $decoded['announce']  =  parent::getAnnouncementUrl(peerId: $peerId);

            // Return the customised bencoded torrent.
            return Bencode::encode($decoded);
        }


        // Return the cached database response.
        return $this->torrentCache[$identifier];
    }

    public function getTorrentListing(
        string $searchQuery =  "",
        string $searchBy    =  "title",
        int    $maxResults  =  50,
        string $sortBy      =  "torrent_id",
        bool   $orderDesc   =  false,
        array  $categories  = array()
    ) {
        $order = "ASC";

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

        // Save the query response to the cache, regardless of what the response is.
        $this->torrentCache['torrent_listing'][$searchQuery] = $this->db->select("torrents",
            [
                "[<]categories" => "category_index",
                "[<]users"      => "uid",
                "[>]groups"     => "gid",
                "[>]peers"      => array("torrents.torrent_id" => "torrent_id")
            ],
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
                "torrents.file_size_calc",
                "categories.category_name",
                "seeders"  => Medoo::raw("SUM(if (peers.seeding = 1 and peers.last_seen > ".$this->peerExpirationTime.", 1, 0))"),
                "leechers" => Medoo::raw("SUM(if (peers.seeding = 0 and peers.last_seen > ".$this->peerExpirationTime.", 1, 0))")
            ],
            $where
        );

        return $this->torrentCache['torrent_listing'][$searchQuery];
    }

    /**
     * Returns byte input in IEC format.
     * @param float $size Input bytes to convert.
     * @param int $precision Level of precision (default is 2DP).
     * @return String Size in IEC format.
     */
    public function bytesFormat(float $size, int $precision = 2): string {
        if ($size <= 0) {
            return "0 B";
        }

        $base = log($size, 1024);
        $suffixes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}