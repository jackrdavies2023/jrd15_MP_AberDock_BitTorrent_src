<?php
    if (!$login->isLoggedIn() || $login->getAccountInfo()['is_guest'] == 1) {
        throw new Exception("Not authorised!");
    }

    require_once("class_torrent.php");
    use Torrent\Torrent;
    $torrent = new Torrent(db: $db);

    $smarty->assign("torrentList", htmlSpecialClean($torrent->getTorrentListing(
        maxResults: 60,
        orderDesc: true,
        getBookmarksUserId: $login->getAccountInfo()['uid']
    )));

    $smarty->assign('pageName', 'Bookmarks');
    $smarty->display('bookmarks.tpl');
?>