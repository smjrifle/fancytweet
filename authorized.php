<?php

session_name('ft');
session_start();
require_once 'config.php';
require_once 'twitteroauth.php';
require_once 'database.php';

$myDB = new Database($db['host'], $db['username'], $db['password'], $db['database']);
$myDB->connect();
//session_regenerate_id();
// previous code here
if (isset($_GET['oauth_token']) || (isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret']))) {
    $_SESSION['oauth_token'] = $_GET['oauth_token'];
    //retrieve request tokens from session and use it for retrieving access token

    $auth = new TwitterOAuth($config['key'], $config['secret'], $_SESSION['request']['oauth_token'], $_SESSION['request']['oauth_token_secret']);

    $params = $auth->getAccessToken();

    if (isset($params['user_id'])) {
        //echo "You signed in with twitter!";
        $_SESSION['user_id'] = $params['user_id'];
        $r = $myDB->fetchRows("SELECT * FROM auth WHERE user_id=" . $params['user_id']);
        if (count($r) == 0) { //store the user info as it doesn't exist already
            $sql = "INSERT INTO auth VALUES('','" . $params['user_id'] . "','" . $params['screen_name'] . "','" . "','" . $params['oauth_token'] . "','" . $params['oauth_token_secret'] . "','')";
            $myDB->query($sql);
            $_SESSION['reg'] = 0;
        } else if (isset($r[0]->password)) {//if the user exists and password too
            $_SESSION['reg'] = 1;
            $sql = "UPDATE auth SET user_name='".$params['screen_name']."', oauth_token='".$params['oauth_token']."', oauth_token_secret='".$params['oauth_token_secret']."';";
            $myDB->query($sql);
        } else { //otherwise the user has previously logged in but not created fancytweet login
            $_SESSION['reg'] = 0;
            $sql = "UPDATE auth SET user_name='".$params['screen_name']."', oauth_token='".$params['oauth_token']."', oauth_token_secret='".$params['oauth_token_secret']."';";
            $myDB->query($sql);
        }
        //header("Location: ".$_SESSION['stage']);
            echo "<script>window.location=\"" . $_SESSION['stage'] . "\"</script>";
    } else {
        echo "Sign In Failed!";
    }
} else if (isset($_GET['denied'])) {
    echo "You denied access!";
} else {
    echo "You are not logged in!";
}


//if (isset($_SESSION['user_id'])){
//
//
//
//
//
//
//
//
// // $myDB->query($sql);
//
//  //print_r ($params);
//  //echo $myDB->query;
//
//  //$query="https://api.twitter.com/1/statuses/friends_timeline.xml";
//  //print_r($myAuth->doQuery($query,$params['oauth_token'],$params['oauth_token_secret']));
//}
/*
echo "<br/>Taking you back to where you were..";
echo "<script>window.location=\"" . $_SESSION['stage'] . "\"</script>";
 * 
 */
?>
