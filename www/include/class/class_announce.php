<?php
namespace Announce;

use Config\Config;
use Exception;
use Medoo\Medoo;
use Account\Account;

require_once("utility_functions.php");

class Announce extends Config {
    protected $cache,
              $peerExpirationTime;

    function __construct (
        Medoo &$db,
    ) {
        // PHP equivalent of "super".
        parent::__construct(db: $db);

        // If the peer hasn't updated its status within 30 seconds of the announcement interval, deem it dead.
        $this->peerExpirationTime = time() - (intval(parent::getConfigVal("announcement_interval")) + 30);
    }


    /**
     * Returns the 'peer_id' REQUEST parameter in sha256(bin2hex()) form, with the torrent info_hash combined with
     * the peer_id. This value is randomly generated by the BitTorrent client and is not related to a users 'pid'.
     * @return string|null
     */
    public function getClientID(): string|null {
        if (isset($_REQUEST['peer_id'])) {
            // There are no guidelines for generating this ID, so
            // it will probably be easier if we hex and hash it, to avoid
            // potential problems.

            return hash('sha256', bin2hex($_REQUEST['peer_id'] . $this->getClientInfoHash()));
        }

        return null;
    }

    /**
     * Returns the 'pid' REQUEST parameter. This is not the same as 'peer_id'. This parameter
     * identifies which user account the torrent client is using.
     * @return string|null
     */
    public function getClientPID(): string|null {
        if (isset($_REQUEST['pid']) && !empty(trim($_REQUEST['pid']))) {
            return trim($_REQUEST['pid']);
        }

        return null;
    }

    /**
     * Returns the 'info_hash' REQUEST parameter. This is the hash sum of the torrent being downloaded/seeded.
     * @return string
     */
    public function getClientInfoHash(): string {
        if (isset($_REQUEST['info_hash']) && !empty(trim($_REQUEST['info_hash']))) {
            // Torrent clients don't submit the info_hash as a raw sha1 sum. We need to convert it.
            return bin2hex($_REQUEST['info_hash']);
        }

        throw new Exception("No info hash provided!");
    }

    /**
     * Returns the 'port' REQUEST parameter. Used to tell the tracker which port the client is listening on.
     * @return int
     */
    public function getClientPort(): int {
        if (isset($_REQUEST['port']) && is_numeric($_REQUEST['port'])) {
            return intval($_REQUEST['port']);
        }

        throw new Exception("No port provided!");
    }

    /**
     * Returns the 'uploaded' REQUEST parameter. This is how much the client says
     * it has uploaded.
     * @return mixed|null
     */
    public function getClientUploaded() {
        if (isset($_REQUEST['uploaded']) && is_numeric($_REQUEST['uploaded'])) {
            return $_REQUEST['uploaded'];
        }

        return null;
    }

    /**
     * Returns the 'downloaded' REQUEST parameter. How much has the client downloaded?
     * @return mixed|null
     */
    public function getClientDownloaded() {
        if (isset($_REQUEST['downloaded']) && is_numeric($_REQUEST['downloaded'])) {
            return $_REQUEST['downloaded'];
        }

        return null;
    }

    /**
     * Returns the 'left' REQUEST parameter. How much is left for the client to download?
     * @return mixed
     */
    public function getClientRemaining() {
        if (isset($_REQUEST['left']) && is_numeric($_REQUEST['left'])) {
            return $_REQUEST['left'];
        }
    }

    /**
     * Returns the 'corrupt' REQUEST parameter. Contains how many corrupt blocks the client has?
     * @return mixed|null
     */
    public function getClientCorrupt() {
        if (isset($_REQUEST['corrupt']) && is_numeric($_REQUEST['corrupt'])) {
            return $_REQUEST['corrupt'];
        }

        return null;
    }

    /**
     * Some random key that Deluge sends in its request. I don't know what it does.
     * @return mixed|null
     */
    public function getClientKey() {
        if (isset($_REQUEST['key'])) {
            return $_REQUEST['key'];
        }

        return null;
    }

    /**
     * What event status did the client send?
     * @return mixed|null
     */
    public function getClientEvent()  {
        if (isset($_REQUEST['event'])) {
            return $_REQUEST['event'];
        }

        return null;
    }

    /**
     * Is the client seeding?
     * @return int
     */
    public function isClientSeeding() {
        if ($this->getClientRemaining() == 0) {
            return 1;
        }

        return 0;
    }

    /**
     * Is the client leeching?
     * @return int
     */
    public function isClientLeeching() {
        if ($this->getClientRemaining() == 0) {
            return 0;
        }

        return 1;
    }

    /**
     * Does the client support cryptography?
     * @return bool|null
     */
    public function isClientSupportCrypto() {
        if (isset($_REQUEST['supportcrypto']) && is_int($_REQUEST['supportcrypto'])) {
            return true;
        }

        return null;
    }

    /**
     * Does the client support compact tracker responses?
     * @return bool|null
     */
    public function isClientSupportCompact()
    {
        if (isset($_REQUEST['compact']) && is_int($_REQUEST['compact'])) {
            if ($_REQUEST['compact'] == 1) {
                return true;
            }
        }

        return null;
    }

    /**
     * Does the client require its 'peer_id' to be specified in the tracker response?
     * @return bool
     */
    public function isClientRequirePeerID(): bool {
        if (isset($_REQUEST['no_peer_id']) && is_int($_REQUEST['no_peer_id'])) {
            if ($_REQUEST['no_peer_id'] == 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Queries the database for all info stored about the client from the 'peers' table.
     * @param Account $account
     * @param int $torrentId
     * @return int|mixed|null
     */
    public function getPeerInfo(
        Account &$account,
        int $torrentId
    ) {
        if (!$clientID = $this->getClientID()) {
            return null;
        }

        if (!isset($this->cache[$clientID])) {
            if ($query = $this->db->get("peers", "*",
                [
                    "client_id"   =>  $clientID,
                    "uid"         =>  $account->getAccount()['uid'],
                    "torrent_id"  =>  $torrentId
                ]
            )) {
                $this->cache[$clientID] = $query;
            } else {
                $this->cache[$clientID] = 0;
            }
        }

        if ($this->cache[$clientID] == 0) {
            return null;
        }

        return $this->cache[$clientID];
    }

    /**
     * Removes the peer from the database.
     * @param Account $account
     * @param int $torrentId The Id of the torrent being shared.
     * @return mixed
     * @throws Exception
     */
    public function unregisterPeer(
        Account &$account,
        int $torrentId
    ) {
        if (!$this->db->delete("peers", [
            "client_id"   =>  $this->getClientID(),
            "client_key"  =>  $this->getClientKey(),
            "uid"         =>  $account->getAccount()['uid'],
            "torrent_id"  =>  $torrentId
        ])) {
            throw new Exception("Failed to remove peer from the database!");
        }
    }

    /**
     * Updates the peer info in the database with newly reported stats from the client.
     * @param Account $account
     * @param int $torrentId
     * @return array|void|null
     * @throws Exception
     */
    public function updatePeer(
        Account &$account,
        int $torrentId
    ) {
        if ($this->getPeerInfo(account: $account, torrentId: $torrentId)) {
            // Peer exists, call registerPeer method with the update argument.
            return $this->registerPeer(account: $account, torrentId: $torrentId, update: true);
        }
    }

    /**
     * Inserts a new peer into the database.
     * @param Account $account
     * @param int $torrentId
     * @param bool $update
     * @return array|null
     * @throws Exception
     */
    public function registerPeer(
        Account &$account,
        int $torrentId,
        bool $update = false
    ) {
        $peerInfo = array(
            "client_id"  => $this->getClientID(),
            "client_key" => $this->getClientKey(),
            "ip_address" => getClientIp(),
            "port"       => $this->getClientPort(),
            "uploaded"   => $this->getClientUploaded(),
            "downloaded" => $this->getClientDownloaded(),
            "remaining"  => $this->getClientRemaining(),
            "corrupt"    => $this->getClientCorrupt(),
            "seeding"    => $this->isClientSeeding(),
            "last_seen"  => time(),
            "agent"      => getClientAgent(),
            "uid"        => $account->getAccount()['uid'],
            "torrent_id" => $torrentId
        );

        if ($update) {
            // We need to compare the previous stats to the ones being presented.
            // The difference will be added to the user account.
            if ($currentStats = $this->getPeerInfo(account: $account, torrentId: $torrentId)) {
                $newDownload  =  0;
                $newUpload    =  0;

                if ($currentStats['downloaded'] < $peerInfo['downloaded']) {
                    // We've downloaded since the last announce.
                    $newDownload = $peerInfo['downloaded'] - $currentStats['downloaded'];
                }

                if ($currentStats['uploaded'] < $peerInfo['uploaded']) {
                    // We've uploaded since the last announce.
                    $newUpload = $peerInfo['uploaded'] - $currentStats['uploaded'];
                }

                // Update the account.
                $account->updateAccount(
                    newData: [
                         "downloaded[+]"  =>  $newDownload,
                         "uploaded[+]"    =>  $newUpload
                    ],
                    updateCache: true
                );

                // Recalculate the account ratio.
                if ($account->getAccount()['downloaded'] == 0) {
                    $newRatio  =  0;
                } else {
                    $newRatio  =  round($account->getAccount()['uploaded'] / $account->getAccount()['downloaded'], 2);
                }

                $account->updateAccount(
                    newData: [
                        "ratio"  =>  $newRatio
                    ]
                );
            }

            if ($this->db->update("peers", $peerInfo,
                [
                    "client_id"   =>  $peerInfo['client_id'],
                    "client_key"  =>  $peerInfo['client_key'],
                    "torrent_id"  =>  $peerInfo['torrent_id']
                ]
            )) {
                // Stats updated.
                return $peerInfo;
            }

            return null;
        }

        $peerInfo['first_seen'] = time();

        if ($this->db->insert("peers", $peerInfo)) {
            return $peerInfo;
        }

        return null;
    }

    public function getPeers(
        int $torrentId,
        bool $extraInfo = false
    ): array {
        $toFetch = [
            "client_id(cid)",
            "client_key(ckey)",
            "ip_address(ip)",
            "port",
            "seeding"
        ];

        if ($extraInfo) {
            $toFetch = array_merge($toFetch, [
                "remaining",
                "last_seen",
                "uploaded",
                "downloaded",
                "agent"
            ]);
        }

        return $this->db->select("peers", $toFetch,
            [
                "torrent_id"    => $torrentId,
                "last_seen[>]"  => $this->peerExpirationTime,
                // We don't want to give our own client to ourself in the list of peers.
                "ip_address[!]" => getClientIp(),
                "port[!]"       => $this->getClientPort()
            ]
        );
    }
}
?>