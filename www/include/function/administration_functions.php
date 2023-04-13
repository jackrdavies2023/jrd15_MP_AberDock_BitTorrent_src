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

        if (isset($_REQUEST['category_name-categoryID'.$category['category_index']])) {
            $newName = trim($_REQUEST['category_name-categoryID'.$category['category_index']]);

            if ($newName !== $name) {
                $update = true;
                $name   = $newName;
            }
        }

        if (isset($_REQUEST['is_parent-categoryID'.$category['category_index']])) {
            $newIsParent = intval($_REQUEST['is_parent-categoryID'.$category['category_index']]);

            if ($newIsParent !== $isParent) {
                $update = true;

                if ($newIsParent > 0) {
                    $isParent = 1;
                } else {
                    $isParent = 0;
                }
            }
        }

        if (isset($_REQUEST['is_child_of-categoryID'.$category['category_index']])) {
            if ($isParent == 0) {
                // Only children can be added to parents.

                $newChildOf = intval($_REQUEST['is_child_of-categoryID'.$category['category_index']]);

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

    function updateGroup(array &$group) {
        global $config;
        $update = array();

        foreach(
            array(
                "group_name"       => "text",
                "group_color"      => "text",
                "is_admin"         => "binary",
                "is_guest"         => "binary",
                "is_new"           => "binary",
                "is_disabled"      => "binary",
                "can_upload"       => "binary",
                "can_download"     => "binary",
                "can_delete"       => "binary",
                "can_modify"       => "binary",
                "can_viewprofile"  => "binary",
                "can_viewstats"    => "binary",
                "can_comment"      => "binary",
                "can_invite"       => "binary",
                "can_useapi"       => "binary"
            ) as $parameter => $type
        ) {
            if (isset($_REQUEST[$parameter.'-groupID'.$group['group_id']])) {
                if ($type == "binary") {
                    $update[$parameter] = 1;
                } else {
                    $update[$parameter] = $_REQUEST[$parameter.'-groupID'.$group['group_id']];
                }
            } else {
                if ($type == "binary") {
                    $update[$parameter] = 0;
                }
            }
        }

        if (count($update) > 0) {
            $config->updateUserGroup(groupID: $group['group_id'], newParameters: $update);
        }
    }

?>