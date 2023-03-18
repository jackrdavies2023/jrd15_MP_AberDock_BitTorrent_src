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

    function __construct(
        Medoo &$db, 
        String $sessionToken = null
    ) {
        $this->db = $db;

        if ($sessionToken) {
            $this->account = $this->getAccount(sessionToken: $sessionToken);
        }
    }

    function getAccount(
        int $userId = null, 
        String $userIdLong = null,
        String $sessionToken = null
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

    function createAccount(
        String $username,
        String $password,
        int $language = 1,
        int $groupID = 0,
        int $invitedBy = null
    ) {
        $this->db->insert("users", 
            [
                "username" => "$username",
                "password" => "This Is A Test",
                "gid"      => 0
            ]
        );
    }
 }

?>