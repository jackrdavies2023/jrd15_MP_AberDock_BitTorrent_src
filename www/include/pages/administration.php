<?php
    try {
        if ($login->isLoggedIn() && $login->getAccountInfo()['is_admin'] == 1) {
                // Yay
        } else {
            throw new Exception("Not authorised!");
        }
    } catch (Exception $e) {
        $smarty->assign('exceptionMessage', $e->getMessage());
        $smarty->assign('exceptionCode', $e->getCode());
    }

    $smarty->assign('pageName', 'Administration');

    // Load administration.tpl Smarty template file.
    $smarty->display('administration.tpl'); 
?>