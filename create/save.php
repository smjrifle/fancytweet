<?php session_start();
if (isset($_POST)){
        require_once '../config.php';
        require_once '../database.php';
$myDB = new Database($db['host'],$db['username'],$db['password'],$db['database']);
$myDB->connect();
        $f=1;
        if ($_POST['pass']!=$_POST['password']) $f=0;
        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $_POST['email'])) $f=0;
        if ($f){
            session_regenerate_id();
            $r=$myDB->query("UPDATE auth SET password='".md5($_POST['pass'])."', email='".$_POST['email']."' WHERE user_id=".$_SESSION['user_id']);
            if($r){
                echo "<br/>Your FancyTweet account has been created for your Twitter Account! You can now use your username and the password you set to  ..";
                
            }
        }
}
?>