<?php session_start();?>
<htmL>
    <head>
<?php

require_once 'database.php';
require_once 'config.php';
$myDB = new Database($db['host'],$db['username'],$db['password'],$db['database']);
$myDB->connect();

if(isset($_SESSION['user_id']))
    {
        $user=$myDB->fetchFirstRow("SELECT * FROM auth WHERE user_id=".$_SESSION['user_id']."");
        
        
    }
    
    ?>






   