<?php

    if (!$login->isLoggedIn() || $login->getAccountInfo()['is_admin'] == 0) {
        throw new Exception("Not authorised!");
    }

    $smarty->assign('pageName', 'Administration');

    $smarty->assign("languages", $config->getLanguages());
    $smarty->assign("config", $config->getConfig());
    $smarty->assign("categories", $config->getTorrentCategories());

    if (isset($_REQUEST["new-category-name"]) && !empty($newCategory = trim($_REQUEST["new-category-name"]))) {
        // We are trying to add a new torrent category.

    }

    // Load administration.tpl Smarty template file.
    $smarty->display('administration.tpl');

?>