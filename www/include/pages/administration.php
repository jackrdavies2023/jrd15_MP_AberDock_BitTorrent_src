<?php

    require_once("administration_functions.php");

    if (!$login->isLoggedIn() || $login->getAccountInfo()['is_admin'] == 0) {
        throw new Exception("Not authorised!");
    }

    // Are we trying to add a new category?
    if (isset($_REQUEST["new-category-name"]) && !empty($newCategory = trim($_REQUEST["new-category-name"]))) {
        $config->addTorrentCategory(categoryName: $newCategory);
    }

    // Are we trying to change any of the category configurations?
    if (isset($_REQUEST['update-categories'])) {
        foreach ($config->getTorrentCategories() as $category) {
            updateCategory($category);

            foreach($category['category_sub'] as $subcategory) {
                updateCategory($subcategory);
            }
        }
    }

    // Are we trying to delete a category?
    if (isset($_REQUEST['delete-category'])) {
        $toDelete = intval($_REQUEST['delete-category']);

        foreach ($config->getTorrentCategories() as $category) {
            if ($category['category_index'] == $toDelete) {
                $config->deleteTorrentCategory($toDelete);
            }

            foreach($category['category_sub'] as $subcategory) {
                if ($subcategory['category_index'] == $toDelete) {
                    $config->deleteTorrentCategory($toDelete);
                }
            }
        }
    }

    // Are we trying to create a new user group?
    if (isset($_REQUEST["new-group-name"]) && !empty($newGroup = trim($_REQUEST["new-group-name"]))) {
        $config->addUserGroup(groupName: $newGroup);
    }

    // Are we trying to delete a user group?
    if (isset($_REQUEST['delete-group'])) {
        $toDelete = intval($_REQUEST['delete-group']);

        $config->deleteUserGroup(groupID: $toDelete);
    }

    // Are we trying to change any of the group configurations?
    if (isset($_REQUEST['update-groups'])) {
        foreach ($config->getUserGroups() as $group) {
            updateGroup($group);
        }
    }

    // Are we trying to change a global configuration?
    if (isset($_REQUEST['update-global'])) {
        $toUpdate = array();

        foreach ($config->getConfig() as $parameter => $value) {
            switch($parameter) {
                // These cases are for checkboxes in authentication-configuration.
                case "login_required":
                case "registration_enabled":
                case "registration_req_invite":
                case "api_enabled":
                    if (isset($_REQUEST['authentication-configuration'])) {
                        //echo("Authentication configuration received"); exit();
                        // We only want to change the configuration values for these parameters
                        // if the "authentication-configuration" field has been sent in the request.
                        // We need to do this as unchecked checkboxes are not posted by browsers, and we need to
                        // know if an option needs to be disabled or not. The same rule applies to other
                        // configuration categories.

                        if (isset($_REQUEST[$parameter]) && !empty($_REQUEST[$parameter])) {
                            $_REQUEST[$parameter] = "1";
                        } else {
                            // Option has not been provided by the client, meaning it is disabled.
                            $_REQUEST[$parameter] = "0";
                        }
                    }

                    break;

                // These cases are for checkboxes in tracker-configuration.
                case "announcement_allow_guest":
                    if (isset($_REQUEST['tracker-configuration'])) {
                        if (isset($_REQUEST[$parameter]) && !empty($_REQUEST[$parameter])) {
                            $_REQUEST[$parameter] = "1";
                        } else {
                            // Option has not been provided by the client, meaning it is disabled.
                            $_REQUEST[$parameter] = "0";
                        }
                    }

                    break;

                // These cases are for integers in tracker-configuration.
                case "announcement_interval":
                    if (isset($_REQUEST[$parameter])) {
                        if (intval($_REQUEST[$parameter]) >= 60) {
                            $_REQUEST[$parameter] = intval($_REQUEST[$parameter]);
                        } else {
                            throw new Exception("Invalid announcement interval! Minimum time is 60 seconds!");
                        }
                    }

                    break;

                // These cases are for trimming strings in tracker-configuration.
                case "announcement_url":
                    if (isset($_REQUEST[$parameter]) && !empty(trim($_REQUEST[$parameter]))) {
                        $_REQUEST[$parameter] = trim($_REQUEST[$parameter]);
                    }

                    break;

                default:
                    break;
            }

            if (isset($_REQUEST[$parameter]) && $_REQUEST[$parameter] !== $value) {
                // Configuration has been changed, so the DB needs updating.
                $toUpdate[$parameter] = $_REQUEST[$parameter];

            }
        }

        if (count($toUpdate) > 0) {
            // We have settings that need updating.
            foreach ($toUpdate as $parameter => $value) {
                $config->updateConfigVal(parameter: $parameter, value: $value);
            }
        }
    }

    $smarty->assign('pageName', 'Administration');
    $smarty->assign("languages", $config->getLanguages());
    $smarty->assign("config", $config->getConfig());
    $smarty->assign("categories", $config->getTorrentCategories());
    $smarty->assign("groups", $config->getUserGroups());

    // Load administration.tpl Smarty template file.
    $smarty->display('administration.tpl');

?>