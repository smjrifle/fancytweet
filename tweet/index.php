<?php
ini_set('session.gc_maxlifetime', '9999999');
ini_set('session.cookie_lifetime', '9999999');
session_name('ft');
session_start();
?>
<html>
    <head>
        <title>Simple Tweet</title>
    </head>
    <body style="margin:0 auto;text-align: center;">


        <?php
        if (isset($_POST['submit']) || isset($_SESSION['user_id'])) {
            require_once '../database.php';
            require_once '../config.php';
            $myDB = new Database($db['host'], $db['username'], $db['password'], $db['database']);
            $myDB->connect();
        }
        if (isset($_SESSION['user_id'])) {
            require_once 'user.php';
        }
        if (isset($_POST['tweet']) && $_POST['tweet']) {
            require_once 'post_user.php';
        }
        if (isset($_POST['submit']) && !isset($_SESSION['user_id'])) {
            require_once 'post_guest.php';
        } elseif (!isset($_SESSION['user_id'])) {
            require_once 'guest.php';
        }
        ?>
        <form name="tweetbox" method="POST">
            Tweet: <br/><textarea style="width:50%" rows="3" name="tweet"><?php if (isset($r->error)) echo $_POST['tweet']; ?></textarea>
            <br/>
            <input type="submit" name="submit" value="Submit"/>
        </form>


        <a href="/">FancyTweet</a>