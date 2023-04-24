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

class Login extends Account
{
    protected $cache;

    function __construct(Medoo &$db) {

        // PHP equivalent of "super".
        parent::__construct(db: $db);
    }

    /**
     * Checks if the user has a valid session token.
     * @return bool True if logged in, false if not.
     */
    function isLoggedIn(): bool {
        if (isset($_COOKIE['session_token'])) {
            if (!empty($sessionToken = trim($_COOKIE['session_token']))) {
                // Session token is not empty. Let's check if it's in the DB
                // and fetch the account information.

                if ($this->account) {
                    // We already have an Account construct. We should use that
                    // so we can take advantage of the cache in the Account class.

                    if (isset(parent::getAccount()['session_token'])) {
                        if (parent::getAccount()['session_token'] == $sessionToken) {
                            return true;
                        }
                    }

                    // Session token invalid. Unset it from the client.
                    setcookie('session_token', "", time() - 3600, "/");
                    return false;
                }

                // Account construct is empty. Look up an account based on session token.
                if (count(
                    parent::getAccount(
                        sessionToken: $sessionToken,
                        clearCache: true
                    )
                ) > 0) {
                    // We have account info. So that means we're logged in. Now we
                    // need to update the session expiration information and IP address.
                    parent::assignSessionKey(updateSession: true);

                    // Update our cookie value.
                    setcookie("session_token",
                        parent::getAccount()['session_token'],
                        parent::getAccount()['expiration'],
                        "/"
                    );


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
    ): bool {
        if (empty($username = trim($username))) {
            throw new Exception("Empty username!", 200);
        }

        if (empty($password = trim($password))) {
            throw new Exception("Empty password!", 201);
        }

        if (count(parent::getAccount(username: $username, clearCache: true)) > 0) {
            // We have account info. Now we can verify the password against the bcrypt hash.
            if (password_verify($password, parent::getAccount()['password'])) {
                // Password is valid! Register a session token.
                parent::assignSessionKey(remember: $remember);

                setcookie("session_token", 
                    parent::getAccount()['session_token'], 
                    parent::getAccount()['expiration'], 
                    "/"
                );

                return true;
            } else {
                throw new Exception("Invalid password!");
            }
        }

        throw new Exception("Invalid login credentials!", 202);
    }

    public function logInAsGuest() {
        return parent::getGuestAccount();
    }

    function logOut(): bool {
        parent::destroySessionKey();
        return true;
    }

    function getAccountInfo(): array {
        return parent::getAccount();
    }
}
?>