<?php
    require_once("class_torrent.php");
    use Torrent\Torrent;

    if (isset($_GET['uuid']) && !empty($torrentIdLong = trim($_GET['uuid']))) {
        $torrent = new Torrent(db: $db);

        $smarty->assign("torrentDetails", $torrent->getTorrent(torrentIdLong: $torrentIdLong));
    } else {
        throw new Exception("Torrent UUID not specified!");
    }

    $smarty->assign('pageName', 'Torrent details');

    // Load browse.tpl Smarty template file.
    $smarty->display('viewtorrent.tpl');
?>