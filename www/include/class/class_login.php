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

class Login 
{
    protected $db, 
              $accountDetails,
              $cache;

    function __construct(Medoo $db) {
        $this->db = $db;
    }

    function isLoggedIn() {

    }
}
?>