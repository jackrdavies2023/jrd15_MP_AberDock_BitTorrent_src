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
              $uploadWeekTime,
              $uploadDayTime,
              $topTenSeeders,
              $topTenWorstSeeders,
              $weeklyTraffic,
              $dailyTraffic;

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
        $this->uploadWeekTime =  time() - (60 * 60 * 24 * 7);
        $this->uploadDayTime  =  time() - (60 * 60 * 24);
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

    public function getTopTenSeeders() {
        if (!$this->topTenSeeders) {
            if ($result = $this->db->select("users",
                [
                    "username",
                    "uid_long(uuid)",
                    "downloaded",
                    "uploaded",
                    "ratio"
                ],
                [
                    "LIMIT" => 10,
                    "ORDER" => [
                        "ratio" => "DESC",
                    ],
                    "ratio[>]" => 0.5
                ]
            )) {
                foreach ($result as &$user) {
                    $user['uploaded']    =  bytesFormat($user['uploaded']);
                    $user['downloaded']  =  bytesFormat($user['downloaded']);
                }

                $this->topTenSeeders = $result;
            }
        }

        return $this->topTenSeeders;
    }

    public function getTopTenWorstSeeders() {
        if (!$this->topTenWorstSeeders) {
            if ($result = $this->db->select("users",
                [
                    "username",
                    "uid_long(uuid)",
                    "downloaded",
                    "uploaded",
                    "ratio"
                ],
                [
                    "LIMIT" => 10,
                    "ORDER" => [
                        "ratio" => "DESC",
                    ],
                    "ratio[<]" => 0.5
                ]
            )) {
                foreach ($result as &$user) {
                    $user['uploaded']    =  bytesFormat($user['uploaded']);
                    $user['downloaded']  =  bytesFormat($user['downloaded']);
                }

                $this->topTenWorstSeeders = $result;
            }
        }

        return $this->topTenWorstSeeders;
    }

    public function getWeeklyTraffic() {
        if (empty($this->weeklyTraffic)) {
            if ($request = $this->db->get("statistics",
                [
                    "age",
                    "upload",
                    "download"
                ],
                [
                    "statistic_name"  =>  "traffic_week"
                ]
            )) {
                if ($this->uploadWeekTime > $request['age']) {
                    // Statistic needs resetting as it is older than a week.
                    $newStats = array(
                        "age"       =>  time(),
                        "upload"    =>  0,
                        "download"  =>  0
                    );

                    $this->db->update("statistics", $newStats,
                        [
                            "statistic_name"  =>  "traffic_week"
                        ]
                    );

                    $this->weeklyTraffic           =  $newStats;
                    $this->weeklyTraffic['total']  =  bytesFormat(0);
                } else {
                    $this->weeklyTraffic           =  $request;
                    $this->weeklyTraffic['total']  =  bytesFormat($this->weeklyTraffic['upload'] + $this->weeklyTraffic['download']);
                }
            } else {
                throw new Exception("Failed to retrieve weekly statistics!");
            }
        }

        return $this->weeklyTraffic;
    }

    public function updateWeeklyTraffic(int $newUpload, int $newDownload): void {
        // Make a request to make sure the stat isn't already expired before adding.
        $this->getWeeklyTraffic();

        // Add new values to statistic.
        $this->db->update("statistics",
            [
                "upload[+]"    =>  $newUpload,
                "download[+]"  =>  $newDownload
            ],
            [
                "statistic_name"  =>  "traffic_week"
            ]
        );
    }

    public function getDailyTraffic() {
        if (empty($this->dailyTraffic)) {
            if ($request = $this->db->get("statistics",
                [
                    "age",
                    "upload",
                    "download"
                ],
                [
                    "statistic_name"  =>  "traffic_today"
                ]
            )) {
                if ($this->uploadDayTime > $request['age']) {
                    // Statistic needs resetting as it is older than a day.
                    $newStats = array(
                        "age"       =>  time(),
                        "upload"    =>  0,
                        "download"  =>  0
                    );

                    $this->db->update("statistics", $newStats,
                        [
                            "statistic_name"  =>  "traffic_today"
                        ]
                    );

                    $this->dailyTraffic           =  $newStats;
                    $this->dailyTraffic['total']  =  bytesFormat(0);
                } else {
                    $this->dailyTraffic           =  $request;
                    $this->dailyTraffic['total']  =  bytesFormat($this->dailyTraffic['upload'] + $this->dailyTraffic['download']);
                }
            } else {
                throw new Exception("Failed to retrieve daily statistics!");
            }
        }

        return $this->dailyTraffic;
    }

    public function updateDailyTraffic(int $newUpload, int $newDownload): void {
        // Make a request to make sure the stat isn't already expired before adding.
        $this->getDailyTraffic();

        // Add new values to statistic.
        $this->db->update("statistics",
            [
                "upload[+]"    =>  $newUpload,
                "download[+]"  =>  $newDownload
            ],
            [
                "statistic_name"  =>  "traffic_today"
            ]
        );
    }
}

?>