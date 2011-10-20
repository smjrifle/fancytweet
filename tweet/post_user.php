<?php

require_once '../twitteroauth.php';
$auth = new TwitterOAuth($config['key'], $config['secret'], $user->oauth_token, $user->oauth_token_secret);
$r=$auth->post('statuses/update', array('status' => $_POST['tweet']));
if (isset($r->error))
        echo "Error: ".$r->error;
    else 
    echo "Status Updated!";
?>
