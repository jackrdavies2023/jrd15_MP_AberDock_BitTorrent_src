<?php
    require_once("class_statistics.php");
    use Statistics\Statistics;

    $statistics = new Statistics(db: $db);

    $smarty->assign('totalPeers', $statistics->getTotalPeers());
    $smarty->assign("totalWeekUploads", $statistics->getTotalUploadsThisWeek());


    $smarty->assign('pageName', 'Statistics');

    // Load browse.tpl Smarty template file.
    $smarty->display('statistics.tpl');
?>