<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    // Some default variables until we hook up the SQL for site settings.
    // Once the configuration is loaded from the DB, most of these variables
    // will be ignored.
    $theme  = "default";
    $requiredDbVersion = "2.2";
    $siteName = "AberDock";

    // Append our class and function directory to the include path.
    set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/include/class"
                                                  .PATH_SEPARATOR.__DIR__."/include/function");

    // Require the Smarty template class, to make theme development easier.
    // Smarty template acquired from: https://www.smarty.net/
    require_once(__DIR__."/include/class/Smarty/Smarty.class.php");
    $smarty  = new Smarty();

    // Smarty template settings. This just sets the default values before
    // we load the custom ones from the SQL.
    $smarty->debugging = false; // Used for debugging templates.
    $smarty->setTemplateDir(__DIR__."/include/themes/$theme/"); // Template directory.
    $smarty->setCompileDir("/tmp"); // Smarty compiled template directory.
    $smarty->assign('siteName', $siteName); // Assign the variable "siteName" for use inside of template files.
    $smarty->assign('assetDir', "/include/themes/$theme/assets"); // Set the asset directory, for storing CSS, JS and other assets.

    // Require Medoo Database Framework.
    require_once("class_medoo.php");

    // Require the account class.
    require_once("class_account.php");
    use Account\Account;

    // Require the login class.
    require_once("class_login.php");
    use Login\Login;

    // Require the config class.
    require_once("class_config.php");
    use Config\Config;

    try {
        // Global configuration.
        require_once(__DIR__."/include/config.php");

        // Initialise constructs.
        $config  =  new Config(db: $db);
        $login   =  new Login(db: $db);  // Used for checking if the user is authenticated
                                            // and for performing various account tasks.

        // A check to make sure we have the correct database version.
        if ($config->getDatabaseVersion() !== $requiredDbVersion) {
            throw new Exception("Invalid database version!\n\nRequired version: $requiredDbVersion\nCurrent version: ".$config->getDatabaseVersion(), 300);
        }

        if ($config->getConfigVal("login_required") == 1 && !$login->isLoggedIn()) {
            // User is not logged in and guest access is disabled.
            // So we need to redirect to one of the login pages.
            if (isset($_REQUEST['p']) and !empty($_REQUEST['p'])) {
                switch(trim($_REQUEST['p'])) {
                    case "login":
                    case "register":
                    case "recover":
                        break; // No need to force a redirect if we're already visiting
                               // one of the above pages.
                    default:
                        header('Location: /?p=login');
                        exit();
                }
            } else {
                header('Location: /?p=login');
                exit();
            }
        }

        if ($login->isLoggedIn()) {
            $smarty->assign('accountInfo', htmlSpecialClean($login->getAccountInfo()));
        } else {
            if (!$config->getConfigVal("login_required") == 1) {
                // Guests are allowed to browse. Fetch the guest account details.
                $smarty->assign('accountInfo', htmlSpecialClean($login->logInAsGuest()));
            }
        }
    } catch (Exception $e) {
        $smarty->assign('exceptionMessage', $e->getMessage()."\n\nHave you imported the SQL?");
        $smarty->assign('exceptionCode', $e->getCode());
        $smarty->assign('pageName', 'Error');

        // Load error.tpl Smarty template file.
        $smarty->display('error.tpl');
        exit();
    }

    try {
        // Navigation handler.
        if (isset($_REQUEST['p']) and !empty($_REQUEST['p'])) {
            switch(trim($_REQUEST['p'])) {
                case "browse":
                    require(__DIR__."/include/pages/browse.php");
                    break;
                case "viewtorrent":
                    require(__DIR__."/include/pages/viewtorrent.php");
                    break;
                case "upload":
                    require(__DIR__."/include/pages/upload.php");
                    break;
                case "statistics":
                    require(__DIR__."/include/pages/statistics.php");
                    break;
                case "profile":
                    require(__DIR__."/include/pages/profile.php");
                    break;
                case "accountsettings":
                    require(__DIR__."/include/pages/accountsettings.php");
                    break;
                case "bookmarks":
                    require(__DIR__."/include/pages/bookmarks.php");
                    break;
                case "login":
                    require(__DIR__."/include/pages/login.php");
                    break;
                case "register":
                    require(__DIR__."/include/pages/register.php");
                    break;
                case "recover":
                    require(__DIR__."/include/pages/recover.php");
                    break;
                case "administration":
                    require(__DIR__."/include/pages/administration.php");
                    break;
                default:
                    // No page specified. Redirect to torrent browse page.
                    header('Location: /?p=browse');
                    exit();
            }
        } else {
            // No page specified. Redirect to torrent browse page.
            header('Location: /?p=browse');
            exit();
        }
    } catch (Exception $e) {
        $smarty->assign('exceptionMessage', $e->getMessage());
        $smarty->assign('exceptionCode', $e->getCode());
        $smarty->assign('pageName', 'Error');
        $smarty->display('error.tpl');
        exit();
    }
?>
