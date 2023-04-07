<?php
    // Are we already logged in?
    if ($login->isLoggedIn()) {
        header('Location: /');
        exit();
    }

    $smarty->assign('pageName', 'Registration');

    // Load register.tpl Smarty template file.
    $smarty->display('register.tpl');
?>