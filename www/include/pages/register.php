<?php
    // Are we already logged in?
    if ($login->isLoggedIn()) {
        header('Location: /');
        exit();
    }

    // Is the user trying to register?
    if (isset($_REQUEST['username']) &&
        isset($_REQUEST['password']) &&
        isset($_REQUEST['password-confirmation']) &&
        isset($_REQUEST['language'])
    )
    {
        $remember = false;

        if (isset($_REQUEST['remember']) && $_REQUEST['remember'] == "on") {
            $remember = true;
        }
    }

    $smarty->assign('pageName', 'Registration');

    // Load register.tpl Smarty template file.
    $smarty->display('register.tpl');
?>