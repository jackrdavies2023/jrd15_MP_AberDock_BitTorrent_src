<?php
    $smarty->assign('pageName', 'Browse');

    // Fetch the list of parent and child categories from the database and assign it
    // as a variable to the template.
    $smarty->assign("categories", $config->getTorrentCategories());

    // Load browse.tpl Smarty template file.
    $smarty->display('browse.tpl');
?>