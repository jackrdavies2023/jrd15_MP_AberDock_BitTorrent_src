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

use Exception;
use Medoo\Medoo;

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
     * @param string $sessionToken The login session token to retrieve the account info for.
     * @param int $userId The ID of the account to retrieve.
     * @param string $userIdLong The long ID of the account to retrieve.
     * @param string $username The username of the account to retrieve.
     */
    function __construct(
        Medoo &$db, 
        string $sessionToken = null,
        int $userId = null, 
        string $userIdLong = null,
        string $username = null
    ) {
        $this->db = $db;

        if ($sessionToken) {
            $this->account = $this->getAccount(sessionToken: $sessionToken);
            return;
        }

        if ($userId) {
            $this->account = $this->getAccount(userId: $userId);
            return;
        }

        if ($userIdLong) {
            $this->account = $this->getAccount(userIdLong: $userIdLong);
            return;
        }

        if ($username) {
            $this->account = $this->getAccount(username: $username);
            return;
        }
    }

    /**
     * Fetches information regarding an account.
     * 
     *  Exception codes:
     *     103 - Account search parameters not specified (not enough arguments)
     * 
     * @param int $userId The ID of the account to retrieve.
     * @param string $userIdLong The long ID of the account to retrieve.
     * @param string $sessionToken The login session token to retrieve the account info for.
     * @param string $username The username of the account to retrieve.
     * @return array An array of the account details.
     */
    function getAccount(
        int $userId = null, 
        string $userIdLong = null,
        string $sessionToken = null,
        string $username = null,
        bool $clearCache = false
    ): array {
        if ($clearCache) {
            $this->account = null;
        }

        if (is_array($this->account)) {
            // We've already retrieved the account info. Return that instead
            // of making another query to the database.
            return $this->account;
        }

        // These are the things we always want to retrieve, regardless of how
        // we're looking up an account.
        $what = array(
            "users.username",
            "users.password",
            "users.uid_long",
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
                "sessions.ip_address"
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
            return $this->account = $query;
        }

        // Even if there is no account data, we'll cache it
        // So we don't make the same request twice.
        return $this->account = array();
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
     */
    function assignSessionKey(
        bool $remember = false,
    ): array {
        if (!$remember) {
            $expiration = time() + $this->defaultExpirationTime;
            $remember = 0;
        } else {
            // "Remember me" extends the lifespan of a session token.
            $expiration = time() + $this->defaultMaxExpirationTime;
            $remember = 1;
        }

        if (count($this->getAccount()) > 0) {
            // We have an account to bind the session key to. Generate random key.
            $token = hash('sha256', $this->getAccount()['username'] . time() . rand(32, 32));

            if ($this->db->insert("sessions",
                [
                    "session_token" => $token,
                    "uid"           => $this->getAccount()['uid'],
                    "last_seen"     => time(),
                    "expiration"    => $expiration,
                    "agent"         => trim($_SERVER['HTTP_USER_AGENT']),
                    "remember"      => $remember,
                    "ip_address"    => $_SERVER['REMOTE_ADDR'] // This should be changed at some point,
                                                               // to take into account proxy servers.
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
                "invited_by" => $invitedBy
            ]
        )) {
            // Account created!
            return true;
        }

        throw new Exception("Failed to create account!", 102);
    }
}
?>