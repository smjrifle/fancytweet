<?php

require_once '../twitteroauth.php';
$auth = new TwitterOAuth($config['key'], $config['secret'], $user->oauth_token, $user->oauth_token_secret);
$r = $auth->post('statuses/update', array('status' => html_entity_decode($_POST['tweet'], ENT_NOQUOTES, 'UTF-8')));
if (isset($r->error))
    echo "<div style='color:red'>Error: " . $r->error . "</div>";
else
    echo "Status Updated!";
?>
