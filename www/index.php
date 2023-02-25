<?php
	// Global configuration.
	require_once(__DIR__."/include/config.php");

	// Require the Smarty template class, to make theme development easier.
	require_once(__DIR__."/include/class/Smarty/Smarty.class.php");


	// Initialise constructs.
	$smarty  = new Smarty(); // Used for loading themes/templates.


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
