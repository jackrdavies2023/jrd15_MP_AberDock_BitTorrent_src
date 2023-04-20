<?php
/**
 * Class Torrent
 *
 * This class is for uploading, editing and retrieving new torrents.
 *
 * Written by Jack Ryan Davies (jrd15)
 */

namespace Torrent;

use Exception;
use Medoo\Medoo;
use Bencode\Bencode;
use Config\Config;
use Account\Account;

class Torrent extends Config
{

    function __construct(
        Medoo &$db
    ) {
        // PHP equivalent of "super".
        parent::__construct(db: $db);
    }

    function addTorrent(
        string $title,
        string $description,
        int $categoryIndex,
        string $coverImagePath = null,
        string $torrentFilePath,
        int $userId,
        int $isAnonymous = 0
    ) {

        if (!file_exists($torrentFilePath)) {
            throw new Exception("Torrent file does not exist! Cannot import!");
        }

        if (!$decoded = Bencode::decode(file_get_contents($torrentFilePath))) {
            throw new Exception("Failed to parse torrent file!");
        }

        if (count($decoded['info']['files']) <= 0) {
            throw new Exception("Torrent contains no files!");
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

        $infoHash      = sha1(Bencode::encode($decoded['info']));
        $torrentIdLong =  $this->db->raw("UUID()");

        $toInsert = array(
            "uid"             =>  $userId,
            "anonymous"       =>  $isAnonymous,
            "category_index"  =>  $categoryIndex,
            "info_hash"       =>  $infoHash,
            "torrent_id_long" => $torrentIdLong,
            "file_name"       =>  "$title",
            "file_size"       =>  $decoded['info']['piece length'],
            "file_size_calc"  =>  "100GiB",
            "title"           =>  "$title",
            "description"     =>  "$description",
            "upload_time"     =>  time(),
            "published"       =>  1,
            "torrent_data"    =>  file_get_contents($torrentFilePath)
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

        return $torrentIdLong;
    }

    function getTorrent(
        string $torrentIdLong
    ) {
        if (empty($torrentIdLong = trim($torrentIdLong))) {
            throw new Exception("Empty torrent ID provided!");
        }

        if (!$torrent = $this->db->get("torrents",
            [
                "[<]users"      => "uid", // We're joining the users table based on the uid.
                "[<]categories" => "category_index"
            ],
            [
                "users.username(uploader)",
                "users.uid_long(uploader_uid)",
                "torrents.torrent_id",
                "torrents.torrent_id_long",
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
                "categories.category_name"
            ],
            [
                "torrent_id_long" => $torrentIdLong
            ]
        )) {
            throw new Exception("Torrent does not exist!");
        }

        return $torrent;
    }

    public function getTorrentListing(
        string $searchQuery =  "",
        string $searchBy    =  "title",
        int    $maxResults  =  50,
        string $sortBy      =  "torrent_id",
        bool   $orderDesc   =  false
    ) {
        $order = "ASC";

        if ($orderDesc) {
            $order = "DESC";
        }

        if ($maxResults <= 0 || $maxResults > 100) {
            throw new Exception("Invalid return limit!");
        }


    }
}