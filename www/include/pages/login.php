<?php
    try {
        // Are we already logged in?
        if ($login->isLoggedIn()) {
            if (isset($_GET['logout'])) {
                $login->logOut();
            }


            header('Location: /');
            exit();
        }

        // Is the user trying to authenticate?
        if (isset($_POST['username']) &&
            isset($_POST['password']))
        {
            $remember = false;

            if (isset($_POST['remember']) && $_POST['remember'] == "on") {
                $remember = true;
            }

            if ($login->logIn(
                username: $_POST['username'],
                password: $_POST['password'],
                remember: $remember
            )) {
                // Login success. Redirect.
                header('Location: /');
            }
        }
    } catch (Exception $e) {
        $smarty->assign('exceptionMessage', $e->getMessage()."\n\nHave you imported the SQL?");
        $smarty->assign('exceptionCode', $e->getCode());
        $smarty->assign('pageName', 'Error');

        // Load error.tpl Smarty template file.
        $smarty->display('error.tpl'); 
        exit();
    }
    


    $smarty->assign('pageName', 'Login');

    // Load login.tpl Smarty template file.
    $smarty->display('login.tpl');
?>