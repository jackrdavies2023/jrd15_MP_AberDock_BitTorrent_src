<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	

	// Global configuration.
	require_once(__DIR__."/include/config.php");

	// Require the Smarty template class, to make theme development easier.
	// Smarty template acquired from: https://www.smarty.net/
	require_once(__DIR__."/include/class/Smarty/Smarty.class.php");

	// Some default variables.
	$theme  = "default";


	// Initialise constructs.
	$smarty  = new Smarty(); // Used for loading themes/templates.

	// Smarty template settings.
	$smarty->debugging = false; // Used for debugging templates.
	$smarty->setTemplateDir(__DIR__."/include/themes/$theme/"); // Template directory.
	$smarty->setCompileDir("/tmp"); // Smarty compiled template directory.

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
