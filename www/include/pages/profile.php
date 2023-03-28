<?php
    use Account\Account;

    try {
        if (isset($_GET['user']) && !empty($userIdLong = trim($_GET['user']))) {
            $account = new Account(db: $db, userIdLong: $userIdLong);

            if (count($account->getAccount()) > 0) {
                // Account exists.
                $smarty->assign('viewProfileDetails', $account->getAccount());
            } else {
                throw new Exception("Account not found!");
            }
        } else {
            throw new Exception("Account not specified!");
        }
    } catch (Exception $e) {
        $smarty->assign('exceptionMessage', $e->getMessage());
        $smarty->assign('exceptionCode', $e->getCode());
    }


    $smarty->assign('pageName', 'Profile');

    // Load browse.tpl Smarty template file.
    $smarty->display('profile.tpl');
?>