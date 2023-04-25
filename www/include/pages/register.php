<?php
    // Are we already logged in?
    if ($login->isLoggedIn()) {
        header('Location: /');
        exit();
    }

    require_once("class_account.php");
    use Account\Account;

    // Is the user trying to register?
    if (isset($_REQUEST['username']) &&
        isset($_REQUEST['password']) &&
        isset($_REQUEST['password-confirmation']) &&
        isset($_REQUEST['language'])
    )
    {
        $remember = false;

        if ($_REQUEST['password'] !== $_REQUEST['password-confirmation']) {
            throw new Exception("Password confirmation does not match!");
        }

        if (intval(($_REQUEST['language'])) <= 0) {
            throw new Exception("Invalid language ID!");
        }

        if (isset($_REQUEST['remember']) && $_REQUEST['remember'] == "on") {
            $remember = true;
        }

        $newAccount = new Account(db: $db);
        if ($newAccount->createAccount(
            username: $_REQUEST['username'],
            password: $_REQUEST['password'],
            language: $_REQUEST['language']
        )) {
            // Account created. Log in!
            if ($login->logIn(
                username: $_REQUEST['username'],
                password: $_REQUEST['password'],
                remember: $remember
            )) {
                // Login success. Redirect.
                header('Location: /');
                exit();
            } else {
                throw new Exception("Failed to log into new account!");
            }
        }
    }

    $smarty->assign('pageName', 'Registration');

    // Load register.tpl Smarty template file.
    $smarty->display('register.tpl');
?>