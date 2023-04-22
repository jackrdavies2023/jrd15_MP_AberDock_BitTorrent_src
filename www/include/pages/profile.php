<?php
    use Account\Account;

    if (isset($_GET['uuid']) && !empty($userIdLong = trim($_GET['uuid']))) {
        $account = new Account(db: $db, userIdLong: $userIdLong);

        if (count($account->getAccount()) > 0 && $account->getAccount()['is_guest'] == 0) {
            // Account exists and is not a guest.

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