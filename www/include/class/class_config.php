<?php
/**
 * Class Config
 *
 * This class is for accessing and updating global site configuration settings.
 *
 * Depends on: Medoo
 * 
 * Written by Jack Ryan Davies (jrd15)
 **/


namespace Config;

use Exception;
use Medoo\Medoo;

class Config
{
    protected $config,
              $groups,
              $categories,
              $languages,
              $db;
    function __construct (
        Medoo &$db,
    ) {
        $this->db = $db;

        // Now lets fetch the site settings from the database.
        if ($config = $this->db->select("config", [
            "config_name" => ["config_value"]
        ])) {
            // Success! Now we need to re-arrange the array so it's in the format of KEY => VALUE
            // rather than KEY => array( VALUE ).

            $newConfig = array();

            foreach ($config as $param => $value) {
                $newConfig[$param] = $value['config_value'];
            }

            $this->config = $newConfig;
        } else {
            throw new Exception("Failed to query global system settings!", 301);
        }
    }

    /**
     * Gets all of the system configuration values.
     * @return array An array of the configuration values.
     */
    public function getConfig(): array {
        return $this->config;
    }

    /**
     * @param  String $config Name of global site configuration key.
     * @return mixed Returns the value of the provided key.
     * @throws Exception 302 error thrown when the configuration option does not exist.
     */
    public function getConfigVal(string $parameter) {
        if (!isset($this->config[$parameter])) {
            throw new Exception("Setting '$parameter' does not exist!", 302);
        }

        return $this->config[$parameter];
    }

    /**
     * Updates a global configuration parameter.
     * @param string $config The name of the configuration parameter.
     * @param string $value The value of the configuration parameter.
     * @return true True when update is a success.
     * @throws Exception Exception when a configuration parameter is invalid or the database has failed.
     */
    public function updateConfigVal(string $parameter, string $value) {
        if (!isset($this->config[$parameter])) {
            throw new Exception("Setting '$parameter' does not exist!", 302);
        }

        if ($this->db->update("config",
            [
                "config_value" => $value
            ],
            [
                "config_name"  => $parameter
            ]
        )) {
            // Success. Update the cache.
            $this->config[$parameter] = $value;
            return true;
        }

        throw new Exception("Failed to update configuration table!");
    }

    /**
     * Retrieves the version of the SQL database. This is used to ensure that the current version of the product
     * is using the appropriate database revision.
     * @return string The database version
     * @throws Exception 302 error thrown when the configuration option does not exist.
     */
    public function getDatabaseVersion(): string {
        return (string)$this->getConfigVal("database_version");
    }

    /**
     * Fetches the list of  groups and their permissions.
     * @return array An array of each user group and its permissions.
     * @throws Exception 303 Failed to retrieve the user groups.
     */
    public function getUserGroups(): array {
        if (!$this->groups) {
            // We haven't fetched the user groups before, so lets make a request to the SQL DB.
            if ($groups = $this->db->select("groups", [
                "gid" => [
                    "group_name",
                    "gid(group_id)",
                    "group_color",
                    "is_admin",
                    "is_guest",
                    "is_new",
                    "is_disabled",
                    "can_upload",
                    "can_download",
                    "can_delete",
                    "can_modify",
                    "can_viewprofile",
                    "can_viewstats",
                    "can_comment",
                    "can_invite",
                    "can_useapi"
                ]
            ])) {
                // Success!
                $this->groups = $groups;
            } else {
                throw new Exception("Failed to fetch user groups!", 303);
            }
        }

        return $this->groups;
    }

    /**
     * Creates a new user group.
     * @param string $groupName The name of the group.
     * @return true Returns true on successful group creation.
     * @throws Exception Exception if the group already exists or if the DB was unable to insert.
     */
    public function addUserGroup(string $groupName) {
        $groupName = trim($groupName);

        foreach ($this->getUserGroups() as $group => $parameters) {
            if ($group == $groupName) {
                throw new Exception("User group already exists with that name!");
            }
        }

        // Category doesn't exist. Let's insert it into the DB.

        if ($this->db->insert("groups",
            [
                "group_color" => "ffffff",
                "group_name"  => $groupName
            ]
        )) {
            // Now that we've added a new group, we'll clear the group cache so the next request to it
            // will reflect our changes.
            $this->clearUserGroupCache();

            return true;
        }

        throw new Exception("Failed to add new group!");
    }

    public function deleteUserGroup(int $groupID) {
        if ($this->doesUserGroupExist(groupID: $groupID)) {
            if (!$this->db->delete("groups",
                [
                    "gid" => $groupID
                ]
            )) {
                throw new Exception("Failed to delete user group!");
            }

            // Group deleted. Clear the group cache so changes are immediately reflected.
            $this->clearUserGroupCache();

            return true;
        }

        throw new Exception("User group does not exist!");
    }

    public function updateUserGroup(
        int $groupID,
        array $newParameters
    ) {
        if (count($newParameters) > 0) {
            if ($this->doesUserGroupExist(groupID: $groupID)) {
                foreach ($newParameters as $parameter => $value) {
                    switch($parameter) {
                        case "group_name":
                        case "group_color":
                            $value = trim($value);

                            if (empty($value)) {
                                throw new Exception("Group name or colour cannot be empty!");
                            }

                            if ($this->getUserGroups()[$groupID][$parameter] == $value) {
                                // Group parameter has not changed. Unset it from the update array.
                                unset($newParameters[$parameter]);
                            } else {
                                $newParameters[$parameter] = $value;
                            }

                            break;
                        case "is_admin":
                        case "is_guest":
                        case "is_new":
                        case "is_disabled":
                        case "can_upload":
                        case "can_download":
                        case "can_delete":
                        case "can_modify":
                        case "can_viewprofile":
                        case "can_viewstats":
                        case "can_comment":
                        case "can_invite":
                        case "can_useapi":
                            if ($value) {
                                $value = 1;
                            } else {
                                $value = 0;
                            }

                            if ($this->getUserGroups()[$groupID][$parameter] == $value) {
                                // Group parameter has not changed. Unset it from the update array.
                                unset($newParameters[$parameter]);
                            } else {
                                $newParameters[$parameter] = $value;
                            }

                            break;
                        default:
                            throw new Exception("Invalid group parameter provided!");
                    }
                }
            } else {
                throw new Exception("Invalid user group ID provided!");
            }

            if (count($newParameters) > 0) {
                // We still have parameters that need updating.
                if (!$this->db->update("groups",
                    $newParameters,
                    [
                        "gid" => $groupID
                    ]
                )) {
                    throw new Exception("Failed to update user groups!");
                }

                // Clear group cache, so changes are reflected immediately.
                $this->clearUserGroupCache();
            }
        }

        return true;
    }

    public function doesUserGroupExist(int $groupID) {
        if (isset($this->getUserGroups()[$groupID])) {
            return true;
        }

        return false;
    }

    public function clearUserGroupCache() {
        $this->groups = null;

        return true;
    }

    /**
     * Retrieves all torrent categories.
     * @return array An array of all torrent categories.
     * @throws Exception Exception if cannot retrieve categories from the database.
     */
    public function getTorrentCategories(): array {
        if (!$this->categories) {
            // We haven't fetched torrent categories before, so lets make a request to the SQL DB.

            if ($categories = $this->db->select("categories",
                [
                    "category_index",
                    "category_subof",
                    "category_name"
                ],
                [
                    "ORDER" => "category_index"
                ]
            )) {
                // Success! Now we need to modify the returned array so that children categories are placed
                // inside of the parent.

                $newCategories = array();

                for ($i = 0; $i < count($categories); $i++) {
                    if ($categories[$i]['category_subof'] == 0) {
                        // This is a parent category.
                        $newCategories[$categories[$i]['category_index']] = array(
                            "category_index" => $categories[$i]['category_index'],
                            "category_name"  => $categories[$i]['category_name'],
                            "category_parent" => 1,
                            "category_child_of" => 0,
                            "category_sub"   => array()
                        );
                    } else {
                        // This is a child category.
                        $newCategories[$categories[$i]['category_subof']]['category_sub'][] = array(
                            "category_index"   => $categories[$i]['category_index'],
                            "category_name" => $categories[$i]['category_name'],
                            "category_parent" => 0,
                            "category_child_of" => $categories[$i]['category_subof']
                        );
                    }
                }

                $this->categories = $newCategories;
            } else {
                throw new Exception("Failed to fetch categories!", 304);
            }
        }

        return $this->categories;
    }


    /**
     * Creates a new torrent category.
     * @param string $categoryName The name of the new category.
     * @return bool True on success.
     * @throws Exception Exception when a category already exists or if DB import fails.
     */
    public function addTorrentCategory(string $categoryName): bool {
        $categoryName = trim($categoryName);

        foreach ($this->getTorrentCategories() as $category) {
            if ($category['category_name'] == $categoryName) {
                throw new Exception("Category already exists with that name!");
            }

            foreach ($category['category_sub'] as $child) {
                if ($child['category_name'] == $categoryName) {
                    throw new Exception("Category already exists with that name!");
                }
            }
        }

        // Category doesn't exist. Let's insert it into the DB.

        if ($this->db->insert("categories",
            [
                "category_subof" => 0,
                "category_name"  => $categoryName
            ]
        )) {
            // Now that we've added a new category, we'll clear the category cache so the next request to it
            // will reflect our changes.
            $this->clearTorrentCategoryCache();

            return true;
        }

        throw new Exception("Failed to add new category!");
    }

    /**
     * Updates a categories configuration.
     * @param int $categoryIndex Index ID of the category.
     * @param string|null $categoryName New name of the category.
     * @param int|null $isParent 1 = Category to be a parent, 0 = Category to be a child.
     * @param int|null $childOf Index ID of the parent category to be a child of.
     * @return true True on success.
     * @throws Exception Exception if update parameters are invalid or cannot update the DB.
     */
    public function updateTorrentCategory(
        int $categoryIndex,
        string $categoryName = null,
        int $isParent = null,
        int $childOf = null
    ) {
        $toUpdate = array();

        if ($this->doesTorrentCategoryExist(categoryIndex: $categoryIndex)) {
            // Category we're trying to update exists.

            if ($childOf > 0) {
                // We're trying to update the category child parameter. Check if it's a child of a real parent.

                if ($this->isTorrentCategoryParent(categoryIndex: $childOf)) {
                    // Parent category exists!

                    if ($categoryIndex == $childOf) {
                        throw new Exception("Cannot set category as a child of itself!");
                    }
                    $toUpdate['category_subof'] = $childOf;
                } else {
                    throw new Exception("Specified parent category is invalid!");
                }
            }

            if ($isParent) {
                $toUpdate['category_subof'] = 0;
            } else {
                if (isset($this->categories[$categoryIndex]['category_sub']) &&
                    count($this->categories[$categoryIndex]['category_sub']) > 0
                ) {
                    throw new Exception("Cannot change parent to a child if the parent has children!");
                }
            }

            if ($categoryName && !empty($categoryName = trim($categoryName))) {
                $toUpdate['category_name'] = $categoryName;
            }

            if ($this->db->update("categories", $toUpdate,
                [
                    "category_index" => $categoryIndex
                ]
            )) {
                // Category updated. We should clear the cache so the updates are reflected on next request.
                $this->clearTorrentCategoryCache();
                return true;
            }

            throw new Exception("Failed to update torrent category!");
        } else {
            throw new Exception("Category does not exist!");
        }
    }

    /**
     * Deletes a torrent category.
     * @param int $categoryIndex Index ID of a category.
     * @return true True on successful delete.
     * @throws Exception Exception if trying to delete a parent with children; the category doesn't exist;
     *                   trying to delete the last parent or there was an issue with the DB.
     */
    public function deleteTorrentCategory(int $categoryIndex) {
        if ($this->doesTorrentCategoryExist(categoryIndex: $categoryIndex)) {
            if ($this->isTorrentCategoryParent(categoryIndex: $categoryIndex)) {
                // This is a parent category. We need to check if there are children. If there are
                // then we cannot delete the category.

                if (isset($this->categories[$categoryIndex]['category_sub']) &&
                    count($this->categories[$categoryIndex]['category_sub']) > 0
                ) {
                    throw new Exception("Cannot delete a parent category that has children!");
                }
            }
        } else {
            throw new Exception("Category does not exist!");
        }

        if (count($this->getTorrentCategories()) == 1 &&
            $this->isTorrentCategoryParent(categoryIndex: $categoryIndex)
        ) {
            // We're trying to delete our last parent!
            throw new Exception("Cannot delete the last parent category!");
        }

        if ($this->db->delete("categories",
            [
                "category_index" => $categoryIndex
            ]
        )) {
            // Delete success. Clear the categories cache so the changes are reflected immediately.
            $this->clearTorrentCategoryCache();
            return true;
        }

        throw new Exception("Failed to delete category!");
    }

    /**
     * Queries if a category is a parent or not.
     * @param int $categoryIndex Index ID of a category.
     * @return bool True if the category is a parent. False if it is a child.
     * @throws Exception Exception if getTorrentCategories fails to retrieve torrent categories.
     */
    public function isTorrentCategoryParent(int $categoryIndex) {
        foreach ($this->getTorrentCategories() as $category) {
            if ($categoryIndex == $category['category_index']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Queries if a category exists.
     * @param int $categoryIndex Index ID of a category.
     * @return bool True if the category exists. False if it does not.
     * @throws Exception Exception if getTorrentCategories fails to retrieve torrent categories.
     */
    public function doesTorrentCategoryExist(int $categoryIndex) {
        foreach ($this->getTorrentCategories() as $category) {
            if ($categoryIndex == $category['category_index']) {
                return true;
            }

            foreach ($category['category_sub'] as $childCategory) {
                if ($categoryIndex == $childCategory['category_index']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function clearTorrentCategoryCache() {
        $this->categories = null;

        return true;
    }

    /**
     * Queries the list of languages defined in the database.
     * @return array An array of installed language packs.
     * @throws Exception Exception if there is an issue querying the database.
     */
    public function getLanguages(): array {
        if (!$this->languages) {
            // We haven't fetched system languages before, so lets make a request to the SQL DB.

            if ($languages = $this->db->select("languages",
                [
                    "lid",
                    "language_short",
                    "language_long"
                ]
            )) {
                // Success!

                $this->languages = $languages;
            } else {
                throw new Exception("Failed to fetch languages!", 305);
            }
        }

        return $this->languages;
    }
}
?>