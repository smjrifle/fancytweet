<?php
session_start();
?>
<htmL>
    <head>
        <title>Simple Tweet</title>
    </head>
    <body style="margin:0 auto;text-align: center;">
        <form name="tweetbox" method="POST">
            <?php
            if (isset($_POST['submit']) || isset($_SESSION['user_id'])) {
                require_once '../database.php';
                require_once '../config.php';
                $myDB = new Database($db['host'], $db['username'], $db['password'], $db['database']);
                $myDB->connect();
            }

            if (isset($_POST['submit']) && !isset($_SESSION['user_id'])) {
                require_once 'post_guest.php';
            } elseif (!isset($_SESSION['user_id'])) {
                require_once 'guest.php';
            }

            if (isset($_SESSION['user_id'])) {
                require_once 'user.php';
            }
            ?>
            Tweet: <br/><textarea style="width:50%" rows="3" name="tweet"></textarea>
            <br/>
            <input type="submit" name="submit" value="Submit"/>
        </form>
        
        <?php
        if (isset($_POST['tweet']) && $_POST['tweet']) {
                    require_once 'post_user.php';
                }
                ?>