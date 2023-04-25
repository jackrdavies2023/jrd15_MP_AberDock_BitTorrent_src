<?php

namespace Statistics;
use Exception;
use Medoo\Medoo;
use Config\Config;

class Statistics extends Config
{
    protected $totalPeers,
              $totalUploads,
              $peerExpirationTime,
              $uploadWeekTime;

    function __construct(
        Medoo &$db
    ) {
        // PHP equivalent of "super".
        parent::__construct(db: $db);

        $this->totalPeers = -1;
        $this->totalUploads = -1;
        $this->peerExpirationTime = time() - (intval(parent::getConfigVal("announcement_interval")) + 30);
        // 60 seconds * 60 = 1 hour.
        // 1 hour * 24 = 1 day.
        // 1 day * 7 = 1 week.
        $this->uploadWeekTime = time() - (60 * 60 * 24 * 7);
    }

    public function getTotalPeers(): int {
        if ($this->totalPeers == -1) {
            $this->totalPeers = $this->db->count("peers",
                [
                    "last_seen[>]" => $this->peerExpirationTime
                ]
            );
        }

        return $this->totalPeers;
    }

    public function getTotalUploadsThisWeek() {
        if ($this->totalUploads == -1) {
            $this->totalUploads = $this->db->count("torrents",
                [
                    "upload_time[>]" => $this->uploadWeekTime
                ]
            );
        }

        return $this->totalUploads;
    }
}

?>