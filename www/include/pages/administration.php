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

    $smarty->assign("languages", $config->getLanguages());
    $smarty->assign("config", $config->getConfig());
    $smarty->assign("categories", $config->getTorrentCategories());

    // Load administration.tpl Smarty template file.
    $smarty->display('administration.tpl'); 
?>