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
    public function getConfigVal(string $config) {
        if (isset($this->config[$config])) {
            return $this->config[$config];
        } else {
            throw new Exception("Setting '$config' does not exist!", 302);
        }
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
     * Fetches the list of user groups and their permissions.
     * @return array An array of each user group and its permissions.
     * @throws Exception 303 Failed to retrieve the user groups.
     */
    public function getUserGroups(): array {
        if (!$this->groups) {
            // We haven't fetched the user groups before, so lets make a request to the SQL DB.
            if ($groups = $this->db->select("groups", [
                "group_name" => [
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
                    "can_invite"
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
                            "category_sub"   => array()
                        );
                    } else {
                        // This is a child category.
                        $newCategories[$categories[$i]['category_subof']]['category_sub'][] = array(
                            "category_index"   => $categories[$i]['category_index'],
                            "category_name" => $categories[$i]['category_name'],
                            "category_parent" => 0
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
                "category_name"  => trim($categoryName)
            ]
        )) {
            // Now that we've added a new category, we'll clear the category cache so the next request to it
            // will reflect our changes.
            $this->categories = null;

            return true;
        }

        return false;
    }

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