<?php
    require_once("class_torrent.php");
    use Torrent\Torrent;
    $torrent = new Torrent(db: $db);
    $query   = "";

    $smarty->assign('pageName', 'Browse');

    if (isset($_REQUEST['query'])) {
        $query = trim($_REQUEST['query']);
    }

    // Fetch the list of parent and child categories from the database and assign it
    // as a variable to the template.
    $smarty->assign("categories", $config->getTorrentCategories());

    // Save the search query so that it can be set as a default value in the search box.
    $smarty->assign("query", $query);

    /*print_r($torrent->getTorrentListing(
        searchQuery: $query,
        maxResults: 60,
    )); exit();*/

    // Fetch a list of torrents and assign it to the template as a variable.
    $smarty->assign("torrentList", $torrent->getTorrentListing(
        searchQuery: $query,
        maxResults: 60,
    ));

    // Load browse.tpl Smarty template file.
    $smarty->display('browse.tpl');
?>