<?php
    require_once("class_torrent.php");
    use Torrent\Torrent;
    $torrent = new Torrent(db: $db);

    $smarty->assign('pageName', 'Browse');

    // Fetch the list of parent and child categories from the database and assign it
    // as a variable to the template.
    $smarty->assign("categories", $config->getTorrentCategories());

    // Fetch a list of torrents and assign it to the template as a variable.
    $smarty->assign("torrentList", $torrent->getTorrentListing(
        searchQuery: "",
        maxResults: 60,
    ));

    // Load browse.tpl Smarty template file.
    $smarty->display('browse.tpl');
?>