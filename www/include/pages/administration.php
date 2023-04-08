<?php

    /**
     * Checks if there is POST/GET data for a given category, and if it needs updating.
     * @param array $category The category array.
     * @return void
     * @throws Exception Exception thrown if update data is invalid.
     */
    function updateCategory(array &$category) {
        global $config;
        $update      = false;
        $name     = $category['category_name'];
        $isParent = intval($category['category_parent']);
        $childOf  = intval($category['category_child_of']);

        if (isset($_REQUEST['name-categoryID'.$category['category_index']])) {
            $newName = trim($_REQUEST['name-categoryID'.$category['category_index']]);

            if ($newName !== $name) {
                $update = true;
                $name   = $newName;
            }
        }

        if (isset($_REQUEST['is-parent-categoryID'.$category['category_index']])) {
            $newIsParent = intval($_REQUEST['is-parent-categoryID'.$category['category_index']]);

            if ($newIsParent !== $isParent) {
                $update = true;

                if ($newIsParent > 0) {
                    $isParent = 1;
                } else {
                    $isParent = 0;
                }
            }
        }

        if (isset($_REQUEST['is-child-of-categoryID'.$category['category_index']])) {
            if ($isParent == 0) {
                // Only children can be added to parents.

                $newChildOf = intval($_REQUEST['is-child-of-categoryID'.$category['category_index']]);

                if ($newChildOf > 0) {
                    if ($newChildOf !== $childOf) {
                        // We're changing the child category to another one.
                        $update  = true;
                        $childOf = $newChildOf;
                    }
                }
            }
        }

        if ($update) {
            $config->updateTorrentCategory(
                categoryIndex: $category['category_index'],
                categoryName: $name,
                isParent: $isParent,
                childOf: $childOf
            );
        }
    }

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

    $smarty->assign('pageName', 'Administration');
    $smarty->assign("languages", $config->getLanguages());
    $smarty->assign("config", $config->getConfig());
    $smarty->assign("categories", $config->getTorrentCategories());



    // Load administration.tpl Smarty template file.
    $smarty->display('administration.tpl');

?>