<?php
    // Are we already logged in?
    if ($login->isLoggedIn()) {
        header('Location: /');
        exit();
    }

    $smarty->assign('pageName', 'Recover account');

    // Load recover.tpl Smarty template file.
    $smarty->display('recover.tpl');
?>