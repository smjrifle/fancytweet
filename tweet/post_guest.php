<?php
$r = $myDB->fetchFirstRow("SELECT user_id FROM auth WHERE user_name='" . $_POST['username'] . "' AND password='" . md5($_POST['password']) . "'");
            if(isset($r->user_id)){
            $_SESSION['user_id']=$r->user_id;
            $user = $myDB->fetchFirstRow("SELECT * FROM auth WHERE user_id=" . $_SESSION['user_id'] . "");
            }else{
                echo "Incorrect Login!";
                exit;
            }
?>
