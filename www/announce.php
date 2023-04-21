<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/include/class"
                                                   .PATH_SEPARATOR.__DIR__."/include/function");

    // Require the config class.
    require_once("class_config.php");
    use Config\Config;

    // Require Medoo Database Framework.
    require_once("class_medoo.php");

    // Require the account class.
    require_once("class_account.php");
    use Account\Account;

    require_once("class_bencode.php");
    use Bencode\Bencode;

    require_once("class_announce.php");
    use Announce\Announce;

    require_once("class_torrent.php");
    use Torrent\Torrent;

    try {
        require_once(__DIR__."/include/config.php");

        // Initialise constructs.
        $announce  =  new Announce(db: $db);

        // Fetch account info based on peer ID.
        if (!$announce->getClientPID()) {
            if ($announce->getConfigVal("announcement_allow_guest") == 0) {
                // No PID has been provided and guests are not allowed to connect to the tracker.
                // Send a response to the torrent client, informing the user.
                throw new Exception("Guests are not allowed to use this tracker!");
            }

            // Guests are allowed to connect. Fetch the guest account details.
            $account  =  new Account(db: $db, guestAccount: true);
        } else {
            // A PID has been provided. Look up the account.
            $account  =  new Account(db: $db, peerId: $announce->getClientPID());

            if (!$account->getAccount()) {
                throw new Exception("Invalid account PID!");
            }
        }

        // We should have account info now. Check account permissions.
        if ($account->getAccount()['can_download'] == 0) {
            throw new Exception("Account not permitted to download!");
        }

        if ($account->getAccount()['is_disabled'] == 1) {
            throw new Exception("Account disabled!");
        }

        // User has permission to use the tracker. Does the torrent exist?
        $torrent = new Torrent(db: $db);

        if (!$torrent->getTorrent(infoHash: $announce->getClientInfoHash())) {
            throw new Exception("Invalid info hash!");
        }



    } catch (Exception $e) {
        error_log(Bencode::encode($e->getMessage()));
        echo(Bencode::encode(data: $e->getMessage(), announceError: true));
    }
?>