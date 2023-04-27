<?php
    if (!$login->isLoggedIn() || $login->getAccountInfo()['is_guest'] == 1) {
        throw new Exception("Not authorised!");
    }

    $smarty->assign('pageName', 'Account settings');
    $smarty->display('accountsettings.tpl');
?>