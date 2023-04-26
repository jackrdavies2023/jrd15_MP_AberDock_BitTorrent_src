<?php
    require_once("class_statistics.php");
    use Statistics\Statistics;

    $statistics = new Statistics(db: $db);

    $smarty->assign('totalPeers', $statistics->getTotalPeers());
    $smarty->assign("totalWeekUploads", $statistics->getTotalUploadsThisWeek());
    $smarty->assign("topTenSeeders", $statistics->getTopTenSeeders());
    $smarty->assign("topTenWorstSeeders", $statistics->getTopTenWorstSeeders());
    $smarty->assign("trafficToday", $statistics->getDailyTraffic()['total']);
    $smarty->assign("trafficWeek", $statistics->getWeeklyTraffic()['total']);


    $smarty->assign('pageName', 'Statistics');

    // Load browse.tpl Smarty template file.
    $smarty->display('statistics.tpl');
?>