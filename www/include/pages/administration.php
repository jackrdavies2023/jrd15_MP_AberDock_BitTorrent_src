<?php

    if (!$login->isLoggedIn() || $login->getAccountInfo()['is_admin'] == 0) {
        throw new Exception("Not authorised!");
    }

    if (isset($_REQUEST["new-category-name"]) && !empty($newCategory = trim($_REQUEST["new-category-name"]))) {
        $config->addTorrentCategory(categoryName: $newCategory);
    }

    $smarty->assign('pageName', 'Administration');
    $smarty->assign("languages", $config->getLanguages());
    $smarty->assign("config", $config->getConfig());
    $smarty->assign("categories", $config->getTorrentCategories());



    // Load administration.tpl Smarty template file.
    $smarty->display('administration.tpl');

?>