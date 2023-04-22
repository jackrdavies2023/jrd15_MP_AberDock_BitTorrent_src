<?php
    use Account\Account;

    if (isset($_GET['uuid']) && !empty($userIdLong = trim($_GET['uuid']))) {
        if ($userIdLong == $login->getAccount()['uid_long']) {
            // We're trying to view our own account.
            $viewingSelf = true;

            if ($login->getAccount()['is_guest'] == 1) {
                // We're logged in as a guest, which is a system account.
                // Guest accounts shouldn't have stats visible like normal accounts.

                throw new Exception("Account not found!");
            }

            $smarty->assign('viewProfileDetails', $login->getAccount());
        } else {
            $viewingSelf = false;
            $account = new Account(db: $db, userIdLong: $userIdLong);

            if (count($account->getAccount()) > 0 && $account->getAccount()['is_guest'] == 0) {
                // Account exists and is not a guest.
                if ($login->getAccount()['can_viewprofile'] == 0 && !$viewingSelf) {
                    throw new Exception("Not permitted to view other profiles!");
                }

                $smarty->assign('viewProfileDetails', $account->getAccount());
            }  else {
                throw new Exception("Account not found!");
            }
        }

        $smarty->assign("viewingSelf", $viewingSelf);
    } else {
        throw new Exception("Account not specified!");
    }

    $smarty->assign('pageName', 'Profile');

    // Load browse.tpl Smarty template file.
    $smarty->display('profile.tpl');
?>