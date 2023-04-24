<?php
    require_once("class_torrent.php");
    use Torrent\Torrent;

    if (isset($_REQUEST['uuid']) && !empty($torrentIdLong = trim($_REQUEST['uuid']))) {
        $torrent = new Torrent(db: $db);

        if (empty($torrentDetails = $torrent->getTorrent(torrentIdLong: $torrentIdLong))) {
            throw new Exception("Torrent does not exist!");
        }

        $smarty->assign("torrentDetails", htmlSpecialClean($torrentDetails));
    } else {
        throw new Exception("Torrent UUID not specified!");
    }

    if (isset($_REQUEST['download'])) {
        // User is trying to download the .torrent.

        if ($login->getAccountInfo()['can_download'] == 0) {
            throw new Exception("Not authorised!");
        }

        $torrentBlob = $torrent->getTorrent(torrentId: $torrentDetails['torrent_id'],
                             download: true,
                             peerId: $login->getAccountInfo()['pid'],
                             userId: $login->getAccountInfo()['uid']);

        header('Content-Description: File Transfer');
        header('Content-Type: application/torrent');
        header('Content-Disposition: attachment; filename='.$torrentDetails['file_name'].'.torrent');
        header('Content-Transfer-Encoding: binary');
        echo($torrentBlob);
        exit();
    }

    $smarty->assign('pageName', 'Torrent details');

    // Load browse.tpl Smarty template file.
    $smarty->display('viewtorrent.tpl');
?>