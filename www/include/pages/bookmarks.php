<?php
    if (!$login->isLoggedIn() || $login->getAccountInfo()['is_guest'] == 1) {
        throw new Exception("Not authorised!");
    }

    require_once("class_torrent.php");
    use Torrent\Torrent;
    $torrent = new Torrent(db: $db);

    if (isset($_REQUEST['uuid']) &&
        !empty($torrentIdLong = trim($_REQUEST['uuid']))
    ) {
        if (isset($_REQUEST['bookmarkdelete'])) {
            $torrent->addBookmark(
                torrentIdLong: $torrentIdLong,
                userID: $login->getAccount()['uid'],
                delete: true
            );
        }

        if (isset($_REQUEST['bookmark'])) {
            $torrent->addBookmark(
                torrentIdLong: $torrentIdLong,
                userID: $login->getAccount()['uid'],
                delete: false
            );
        }
    }

if (isset($_REQUEST['bookmark'])) {
    if ($login->getAccount()['is_guest'] == 1) {
        throw new Exception("Guests cannot bookmark!");
    }

    $torrent->addBookmark(
        torrentIdLong: $torrentIdLong,
        userID: $login->getAccount()['uid']
    );
}

    $smarty->assign("torrentList", htmlSpecialClean($torrent->getTorrentListing(
        maxResults: 60,
        orderDesc: true,
        getBookmarksUserId: $login->getAccountInfo()['uid']
    )));

    $smarty->assign('pageName', 'Bookmarks');
    $smarty->display('bookmarks.tpl');
?>