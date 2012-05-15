<?php
ini_set('session.gc_maxlifetime', '9999999');
ini_set('session.cookie_lifetime', '9999999');
session_name('ft');
session_start();
?>
<htmL>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php
        require_once 'database.php';
        require_once 'config.php';
        $myDB = new Database($db['host'], $db['username'], $db['password'], $db['database']);
        $myDB->connect();

        if (isset($_SESSION['user_id'])) {
            $user = $myDB->fetchFirstRow("SELECT * FROM auth WHERE user_id=" . $_SESSION['user_id'] . "");
        }
        ?>






