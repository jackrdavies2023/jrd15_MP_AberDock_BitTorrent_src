<?php
/**
 * Class Login
 *
 * This class is used for logging in/registering an account,
 * checking if the current user session is valid and for
 * accessing information about the currently logged in user.
 *
 * Depends on: Medoo, Account
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
              $account,
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

                if ($this->account) {
                    // We already have an Account construct. We should use that
                    // so we can take advantage of the cache in the Account class.

                    if (isset($this->account->getAccount()['session_token'])) {
                        if ($this->account->getAccount()['session_token'] == $sessionToken) {
                            return true;
                        }
                    }

                    // Session token invalid. Unset it from the client.
                    setcookie('session_token', "", time() - 3600, "/");
                    return false;
                }
                
                $this->account = new Account(db: $this->db, sessionToken: $sessionToken);
                if (count($this->account->getAccount()) > 0) {
                    // We have account info. So that means we're logged in.
                    return true;
                }

                // Session token invalid. Unset it from the client.
                setcookie('session_token', "", time() - 3600, "/");
            }
        }

        return false;
    }

    /**
     * Checks the provided username and password. On success, a session is created.
     * 
     * Exception codes:
     *     200 - Empty username
     *     201 - Empty password
     *     202 - Invalid login credentials
     * 
     * @param string $username The username of the account to retrieve.
     * @param string $password The password of the account.
     * @param bool $remember Remember the login session. This increases the expiration time.
     * @return bool True on successfull login.
     */
    function logIn(
        string $username,
        string $password,
        bool $remember = false
    ) {
        if (empty($username = trim($username))) {
            throw new Exception("Empty username!", 200);
        }

        if (empty($password = trim($password))) {
            throw new Exception("Empty password!", 201);
        }

        $account = new Account(db: $this->db, username: $username);
        if (count($account->getAccount()) > 0) {
            // We have account info. Now we can verify the password against the bcrypt hash.
            if (password_verify($password, $account->getAccount()['password'])) {
                // Password is valid! Register a session token.
                $account->assignSessionKey(remember: $remember);

                setcookie("session_token", 
                          $account->getAccount()['session_token'], 
                          $account->getAccount()['expiration'], 
                          "/"
                );

                $this->account = $account;
                return true;
            }
        }

        throw new Exception("Invalid login credentials!", 202);
    }

    function logOut() {
        $this->account->destroySessionKey();
        return true;
    }

    function getAccountInfo() {
        return $this->account->getAccount();
    }
}
?>