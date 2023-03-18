<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	// Append our class directory to the include path.
	set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/include/class");

	// Require the Smarty template class, to make theme development easier.
	// Smarty template acquired from: https://www.smarty.net/
	require_once(__DIR__."/include/class/Smarty/Smarty.class.php");

	// Require Medoo Database Framework.
	require_once("class_medoo.php");

	// Require the account class.
	require_once("class_account.php");

	// Require the login class.
	require_once("class_login.php");

	// Global configuration.
	require_once(__DIR__."/include/config.php");

	// Some default variables.
	$theme  = "default";

	// Set the required namespaces.
	use Login\Login;
	use Account\Account;

	// Initialise constructs.
	$smarty  = new Smarty();        // Used for loading themes/templates.
	$login   = new Login(db: $db);  // Used for checking if the user is authenticated
								    // and for performing various account tasks.

	/*if (!$login->isLoggedIn()) {
		echo("Not logged in. Creating...");
		$account = new Account(db: $db);
		try {
			$account -> createAccount(
				username: "testAccount",
				password: "testPassword"
			);
		} catch (Exception $e) {
			exit("Error! ".$e->getMessage());
		}
	}*/

	// Smarty template settings.
	$smarty->debugging = false; // Used for debugging templates.
	$smarty->setTemplateDir(__DIR__."/include/themes/$theme/"); // Template directory.
	$smarty->setCompileDir("/tmp"); // Smarty compiled template directory.
	$smarty->assign('siteName', 'AberDock'); // Assign the variable "siteName" for use inside of template files.
	$smarty->assign('assetDir', "/include/themes/$theme/assets"); // Set the asset directory, for storing CSS, JS and other assets.

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
?>
