<?php
/**
 * Class Login
 *
 * This class is used for logging in/registering an account,
 * checking if the current user session is valid and for
 * accessing information about the currently logged in user.
 *
 * Depends on: Medoo
 * 
 * Written by Jack Ryan Davies (jrd15)
 **/

namespace Login;

use Exception;
use Medoo\Medoo;
use Account\Account;

class Login
{
    protected $db, 
              $accountDetails,
              $cache;

    function __construct(Medoo &$db) {
        $this->db = $db;
    }

    /**
     * Checks if the user has a valid session token.
     * @return bool True if logged in, false if not.
     */
    function isLoggedIn() {
        if (isset($_COOKIE['session_token'])) {
            if (!empty($sessionToken = trim($_COOKIE['session_token']))) {
                // Session token is not empty. Let's check if it's in the DB
                // and fetch the account information.
                
                $account = new Account(db: $this->db, sessionToken: $sessionToken);
                if (count($account->getAccount()) > 0) {
                    // We have account info. So that means we're logged in.
                    return true;
                }
            }
        }

        return false;
    }
}
?>