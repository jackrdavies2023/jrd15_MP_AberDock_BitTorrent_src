<?php
    try {
        // Are we already logged in?
        if ($login->isLoggedIn()) {
            header('Location: /');
            exit();
        }
    } catch (Exception $e) {
        $smarty->assign('exceptionMessage', $e->getMessage()."\n\nHave you imported the SQL?");
        $smarty->assign('exceptionCode', $e->getCode());
        $smarty->assign('pageName', 'Error');

        // Load error.tpl Smarty template file.
        $smarty->display('error.tpl'); 
        exit();
    }

    $smarty->assign('pageName', 'Recover account');

    // Load recover.tpl Smarty template file.
    $smarty->display('recover.tpl');
?>