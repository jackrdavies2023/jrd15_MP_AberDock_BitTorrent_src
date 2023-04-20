<?php

    if (!$login->isLoggedIn() || $login->getAccountInfo()['can_upload'] == 0) {
        throw new Exception("Not authorised!");
    }

    require_once("class_bencode.php");
    use Bencode\Bencode;

    require_once("class_torrent.php");
    use Torrent\Torrent;

    if (isset($_REQUEST['torrent-upload'])) {
        // Trying to upload.

        foreach (
            // Fields we are expecting from the upload form.
            array(
                "torrent-file",
                "torrent-title",
                "torrent-category",
                "torrent-description",
                "torrent-cover",
                "torrent-anonymous"
            ) as $field
        ) {
            switch($field) {
                case "torrent-file":
                case "torrent-cover":
                    if (empty($_FILES)) {
                        throw new Exception("No files were uploaded!");
                    }

                    if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 4) {
                        // "torrent-cover" is optional, whereas "torrent-file" is not.
                        if ($field == "torrent-file") {
                            throw new Exception("There was an issue with the file upload ($field). Upload status: 4");
                        }
                    }

                    break;
                case "torrent-title":
                case "torrent-description":
                    if (!isset($_REQUEST[$field])) {
                        throw new Exception("Field '$field' was not provided!");
                    }

                    if (empty(trim($_REQUEST[$field]))) {
                        throw new Exception("Field '$field' is empty!");
                    }

                    break;
                case "torrent-category":
                    if (!isset($_REQUEST[$field])) {
                        throw new Exception("Field '$field' was not provided!");
                    }

                    if ($_REQUEST[$field] == 0) {
                        throw new Exception("No category specified!");
                    }

                    break;
                case "torrent-anonymous":
                    if (isset($_REQUEST[$field])) {
                        if ($_REQUEST[$field] == "on" || $_REQUEST[$field] == "1") {
                            $_REQUEST[$field] = 1;
                            break;
                        }
                    }

                    $_REQUEST[$field] = 0;
                    break;
                default:
                    break;
            }
        }

        // We've made sure we have all the POST data we need.
        $torrent = new Torrent(db: $db);

        if ($torrentDetails = $torrent->addTorrent(
            title: $_REQUEST['torrent-title'],
            description: $_REQUEST['torrent-description'],
            categoryIndex: $_REQUEST['torrent-category'],
            coverImagePath: $_FILES['torrent-cover']['tmp_name'],
            torrentFilePath: $_FILES['torrent-file']['tmp_name'],
            userId: $login->getAccountInfo()['uid'],
            isAnonymous: $_REQUEST['torrent-anonymous']
        )) {
            header('Location: /?p=viewtorrent&uuid='.$torrentDetails['torrent_uuid']);
        }

    }

    $smarty->assign('pageName', 'Upload');
    $smarty->assign("categories", $config->getTorrentCategories());

    // Load browse.tpl Smarty template file.
    $smarty->display('upload.tpl');
?>