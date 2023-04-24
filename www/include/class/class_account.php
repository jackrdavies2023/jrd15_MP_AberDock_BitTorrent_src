<?php
/**
 * Class Account
 *
 * This class is used for storing and fetching information about
 * a user account.
 *
 * Depends on: Medoo
 * 
 * Written by Jack Ryan Davies (jrd15)
 **/

namespace Account;

require_once("utility_functions.php");
require_once("class_torrent.php");

use Exception;
use Medoo\Medoo;
use Torrent\Torrent;



class Account
{
    protected $db,
              $account,
              $defaultExpirationTime    = (60 * 60) * 2,   // Expires in 2 hours.
              $defaultMaxExpirationTime = (60 * 60) * 744; // Expires in 1 month.

    /**
     * Account class construct.
     *
     * @param Medoo $db The Medoo database object, to be used to communicate with the SQL DB.
     * @param string|null $sessionToken The login session token to retrieve the account info for.
     * @param int|null $userId The ID of the account to retrieve.
     * @param string|null $userIdLong The long ID of the account to retrieve.
     * @param string|null $username The username of the account to retrieve.
     * @param string|null $peerId
     * @param bool $guestAccount
     * @param bool $getUpAndDown
     * @param int $getUpAndDownLimit
     * @throws Exception
     */
    function __construct(
        Medoo &$db, 
        string $sessionToken = null,
        int $userId = null, 
        string $userIdLong = null,
        string $username = null,
        string $peerId = null,
        bool $guestAccount = false,
        bool $getShareHistory = false,
        int $getShareLimit = 10
    ) {
        $this->db = $db;

        if ($sessionToken) {
            $this->getAccount(sessionToken: $sessionToken, getShareHistory: $getShareHistory, getShareLimit: $getShareLimit);
            return;
        }

        if ($userId) {
            $this->getAccount(userId: $userId, getShareHistory: $getShareHistory, getShareLimit: $getShareLimit);
            return;
        }

        if ($userIdLong) {
            $this->getAccount(userIdLong: $userIdLong, getShareHistory: $getShareHistory, getShareLimit: $getShareLimit);
            return;
        }

        if ($username) {
            $this->getAccount(username: $username, getShareHistory: $getShareHistory, getShareLimit: $getShareLimit);
            return;
        }

        if ($peerId) {
            $this->getAccount(peerId: $peerId);
            return;
        }

        if ($guestAccount) {
            $this->getGuestAccount();
            return;
        }

    }

    function getGuestAccount(): array {
        if (is_array($this->account)) {
            // We've already retrieved the account info. Return that instead
            // of making another query to the database.
            return $this->account;
        }

        // Fetch the user ID of the guest account.
        if ($account = $this->db->get("groups",
            [
                "[<]users"    => "gid"
            ],
            [
                "users.uid"
            ],
            [
                "groups.is_guest" => 1
            ]
        )) {
            if ($this->getAccount(userId: $account['uid'])) {
                return $this->getAccount(userId: $account['uid']);;
            }
        }

        throw new Exception("No guest account or group exists!");
    }

    /**
     * Fetches information regarding an account.
     *
     *  Exception codes:
     *     103 - Account search parameters not specified (not enough arguments)
     *
     * @param int|null $userId The ID of the account to retrieve.
     * @param string|null $userIdLong The long ID of the account to retrieve.
     * @param string|null $sessionToken The login session token to retrieve the account info for.
     * @param string|null $username The username of the account to retrieve.
     * @param string|null $peerId The PID of an account. Used by the tracker/announcement.
     * @param bool $clearCache Erase the account cache before making a new request.
     * @return array An array of the account details.
     * @throws Exception
     */
    function getAccount(
        int $userId = null, 
        string $userIdLong = null,
        string $sessionToken = null,
        string $username = null,
        string $peerId = null,
        bool $clearCache = false,
        bool $getShareHistory = false,
        int $getShareLimit = 10
    ): array {
        if ($clearCache) {
            $this->account = null;
        }

        if (is_array($this->account)) {
            // We've already retrieved the account info. Return that instead
            // of making another query to the database.

            if (!isset($this->account['share_history']) && $getShareHistory) {
                // We need to fetch the users upload and download history.
                $this->getShareHistory(limit: $getShareLimit);
            }

            return $this->account;
        }

        // These are the things we always want to retrieve, regardless of how
        // we're looking up an account.
        $what = array(
            "users.username",
            "users.password",
            "users.uid_long",
            "users.pid",
            "groups.group_name",
            "groups.group_color",
            "groups.is_admin",
            "groups.is_guest ",
            "groups.is_new",
            "groups.is_disabled",
            "groups.can_upload",
            "groups.can_download",
            "groups.can_delete",
            "groups.can_modify",
            "groups.can_viewprofile",
            "groups.can_viewstats",
            "groups.can_comment",
            "groups.can_invite",
            "languages.lid(language_id)", // Rename "lid" to "language_id" in the response.
            "languages.language_short",
            "languages.language_long"
        );

        $where = array();
        $table = "users";
        $join  = array(
            "[<]groups"    => "gid",  // We're joining the groups table based on the gid.
            "[<]languages" => "lid"   // We're joining the languages table based on the lid.
        );

        if ($sessionToken) {
            // We're fetching account information based on a session token.
            // We'll lookup the token and use a left join to link the user ID with
            // the details in the "users" table. We'll also grab the group info
            // by joining that based on the gid (group id).

            // Add additional items to select from the database.
            array_push($what, 
                "sessions.session_token",
                "sessions.remember",
                "sessions.expiration",
                "sessions.agent",
                "sessions.uid",
                "sessions.sid",
                "sessions.ip_address",
                "sessions.last_seen"
            );

            $where = array(
                "session_token" => trim($sessionToken)
            );

            $table = "sessions";
            // Prepend the linking of the users table to the join variable.
            /*$join = array_merge($join, array(
                "[>]users" => "uid"
            ));*/

            // Tried prepending to the original "join" variable, but I kept getting
            // errors, so I say just overwrite the variable entirely.

            $join = array(
                "[>]users"     => "uid",
                "[<]groups"    => "gid",
                "[<]languages" => "lid"
            );
        } else {
            // These SELECT options don't work when selecting directly from the
            // sessions table. So we only use them when selecting based on username or ID.
            array_push($what,
                "users.uid",
                "users.gid"
            );
        }

        if ($userId) {
            $where = array(
                "uid" => $userId
            );
        }

        if ($userIdLong) {
            $where = array(
                "uid_long" => $userIdLong
            );
        }

        if ($username) {
            $where = array(
                "username" => $username
            );
        }

        if ($peerId) {
            $where = array(
                "pid" => $peerId
            );
        }

        if (count($where) == 0) {
            // No search-by parameters were passed to this method.
            // Nothing to search for.
            throw new Exception("No account identifier was specified!", 103);
        }
        
        if ($query = $this->db->get($table,
            $join, // Tables to join.
            $what, // Data we want to fetch from the DB.
            $where // Where statement.
        )) {
            $this->account = $query;

            if (!isset($this->account['share_history']) && $getShareHistory) {
                // We need to fetch the users upload and download history.
                $this->getShareHistory(limit: $getShareLimit);
            }

            return $this->account;
        }

        // Even if there is no account data, we'll cache it
        // So we don't make the same request twice.
        return $this->account = array();
    }

    function getShareHistory(int $limit): void {
        if (!$this->account) {
            throw new Exception("No account to retrieve share history of!");
        }

        /*$this->account['share_history']['downloads'] = $this->db->select("downloads",
            [
                "[<]torrents"  =>  "torrent_id",
                "[>]peers"     => array("torrents.torrent_id" => "torrent_id")
            ],
            [
                "downloads.download_id",
                "downloads.torrent_id",
                "torrents.title",
                "torrents.torrent_id_long(torrent_uuid)"
            ],
            [
                "downloads.uid"    =>  $this->account['uid'],
                "torrents.uid[!]"  =>  $this->account['uid'],
                "LIMIT"            =>  $limit
            ]
        );*/

        /*$this->account['share_history']['uploads'] = $this->db->select("torrents",
            [
                "torrents.title",
                "torrents.torrent_id_long(torrent_uuid)"
            ],
            [
                "torrents.uid" => $this->account['uid'],
                "LIMIT" => $limit
            ]
        );*/

        $torrent = new Torrent(db: $this->db);
        $this->account['share_history']['uploads'] = $torrent->getTorrentListing(maxResults: $limit,
                                                                                 getDownloadHistory: true,
                                                                                 getShareUserId: $this->account['uid']);

        $this->account['share_history']['uploads'] = $torrent->getTorrentListing(maxResults: $limit,
                                                                                 getUploadHistory: true,
                                                                                 getShareUserId: $this->account['uid']);
    }

    /**
     * Generates and assigns a session token to an account.
     *
     * Exception codes:
     *     104 - No account to bind the session token to.
     *     105 - Failed to insert session token into database.
     *
     * @param bool $remember true will result in the session lasting for a month rather than 2 hours.
     * @return array An array of the account details on success, which will contain the session token.
     * @throws Exception
     */
    function assignSessionKey(
        bool $remember = false,
        bool $updateSession = false
    ): array {
        if (count($this->getAccount()) > 0) {
            if (!$remember || isset($this->getAccount()['remember']) && $this->getAccount()['remember'] == 0) {
                $expiration = time() + $this->defaultExpirationTime;
                $remember = 0;
            } else {
                // "Remember me" extends the lifespan of a session token.
                $expiration = time() + $this->defaultMaxExpirationTime;
                $remember = 1;
            }

            if ($updateSession) {
                // We're updating an existing session key.
                if (!isset($this->getAccount()['sid'])) {
                    throw new Exception("Cannot update a session that does not exist!");
                }

                if ($this->db->update("sessions",
                    [
                        "last_seen"   =>  time(),
                        "expiration"  =>  $expiration,
                        "agent"       =>  getClientAgent(),
                        "ip_address"  =>  getClientIp()
                    ],
                    [
                        "sid"         =>  $this->getAccount()['sid']
                    ]
                )) {
                    // Update success. Now reflect the changes in the account cache without making
                    // another SQL request.
                    $this->account['last_seen']   = time();
                    $this->account['expiration']  = $expiration;
                    $this->account['agent']       = getClientAgent();
                    $this->account['ip_address']  = getClientIp();

                    return $this->getAccount();
                }
            }

            // We have an account to bind the session key to. Generate random key.
            $token = hash('sha256', $this->getAccount()['username'] . time() . rand(32, 32));

            if ($this->db->insert("sessions",
                [
                    "session_token" => $token,
                    "uid"           => $this->getAccount()['uid'],
                    "last_seen"     => time(),
                    "expiration"    => $expiration,
                    "agent"         => getClientAgent(),
                    "remember"      => $remember,
                    "ip_address"    => getClientIp()
                ]
            )) {
                // Let's re-query the SQL for the account details, which will
                // now have the session information included.
                return $this->getAccount(sessionToken: $token, clearCache: true);
            }

            throw new Exception("Failed to insert session token!", 105);
        }

        throw new Exception("No account to bind session key to!", 104);
    }

    function destroySessionKey(): bool {
        if (isset($this->getAccount()['session_token'])) {
            $this->db->delete("sessions",
                [
                    "session_token" => $this->getAccount()['session_token']
                ]
            );
        }

        return true;
    }

    /**
     * Creates a new user account.
     *
     * Exception codes:
     *     100 - Username too short (4 characters minimum)
     *     101 - Password too short (8 characters minimum)
     *     102 - Failed to create account (DB error?)
     *
     * @param string $username The new account username (4 characters minimum).
     * @param string $password The new account password (8 characters minimum).
     * @param int $language The language the account will use. ID's are in the database.
     * @param int $groupID The ID of the group which the account should be put in.
     * @param int $invitedBy The ID of the user who invited the new account.
     * @return bool True if account creation success.
     * @throws Exception
     */
    function createAccount(
        string $username,
        string $password,
        int $language = 1,
        int $groupID = 1,
        int $invitedBy = 0
    ): bool {
        $username = trim($username);
        $password = trim($password);

        if (strlen($username) < 4) {
            throw new Exception("Username too short!", 100);
        }

        if (strlen($password) < 8) {
            throw new Exception("Password too short!", 101);
        }

        if ($this->db->insert("users", 
            [
                "username"   => "$username",
                "password"   => password_hash(trim($password), PASSWORD_BCRYPT, array('cost' => 12)),
                "gid"        => $groupID,
                "invited_by" => $invitedBy,
                "pid"        => Medoo::raw("UUID()"),
                "uid_long"   => Medoo::raw("UUID()"),
                "join_date"  => time()
            ]
        )) {
            // Account created!
            return true;
        }

        throw new Exception("Failed to create account!", 102);
    }
}
?>