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
              $account;

    /**
     * Account class construct.
     * 
     * @param Medoo $db The Medoo database object, to be used to communicate with the SQL DB.
     * @param string $sessionToken The login session token to retrieve the account info for.
     * @param int $userId The ID of the account to retrieve.
     * @param string $userIdLong The long ID of the account to retrieve.
     */
    function __construct(
        Medoo &$db, 
        string $sessionToken = null,
        int $userId = null, 
        string $userIdLong = null
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
    }

    /**
     * Fetches information regarding an account.
     * 
     * @param int $userId The ID of the account to retrieve.
     * @param string $userIdLong The long ID of the account to retrieve.
     * @param string $sessionToken The login session token to retrieve the account info for.
     * @return array An array of the account details.
     */
    function getAccount(
        int $userId = null, 
        string $userIdLong = null,
        string $sessionToken = null
    ) {
        if ($this->account) {
            // We've already retrieved the account info. Return that instead
            // of making another query to the database.
            return $this->account;
        }

        if ($sessionToken) {
            return "meow";
        }
    }

    /**
     * Creates a new user account.
     * 
     * Exception codes:
     *     100 - Username too short (4 characters minimum)
     *     101 - Password too short (8 characters minimum)
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
        int $groupID = 0,
        int $invitedBy = 0
    ) {
        $username = trim($username);
        $password = trim($password);

        if (strlen($username) < 4) {
            throw new Exception("Username too short!", 100);
        }

        if (strlen($password) < 8) {
            throw new Exception("Password too short!", 101);
        }

        $this->db->insert("users", 
            [
                "username" => "$username",
                "password" => password_hash(trim($password), PASSWORD_BCRYPT, array('cost' => 12)),
                "gid"      => 0,
                "invited_by" => $invitedBy
            ]
        );
    }
 }

?>