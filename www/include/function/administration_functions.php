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

?>