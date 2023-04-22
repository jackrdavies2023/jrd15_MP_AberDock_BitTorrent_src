<?php
    use Account\Account;

    if (isset($_GET['uuid']) && !empty($userIdLong = trim($_GET['uuid']))) {
        $account = new Account(db: $db, userIdLong: $userIdLong);

        if (count($account->getAccount()) > 0 && $account->getAccount()['is_guest'] == 0) {
            // Account exists and is not a guest. Do we have permission to view the users profile?
            if ($login->getAccount()['can_viewprofile'] == 0 &&
                // We're allowed to view our own profile, even if we don't have the "viewprofile" permission.
                $userIdLong !== $login->getAccount()['uid_long']
            ) {
                throw new Exception("Not permitted to view other profiles!");
            }

            $smarty->assign('viewProfileDetails', $account->getAccount());
        } else {
            throw new Exception("Account not found!");
        }
    } else {
        throw new Exception("Account not specified!");
    }

    $smarty->assign('pageName', 'Profile');

    // Load browse.tpl Smarty template file.
    $smarty->display('profile.tpl');
?>