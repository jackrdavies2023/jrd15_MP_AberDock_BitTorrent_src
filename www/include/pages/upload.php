<?php

    if (!$login->isLoggedIn() || $login->getAccountInfo()['can_upload'] == 0) {
        throw new Exception("Not authorised!");
    }

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

            }
        }
    }



    $smarty->assign('pageName', 'Upload');
    $smarty->assign("categories", $config->getTorrentCategories());

    // Load browse.tpl Smarty template file.
    $smarty->display('upload.tpl');
?>