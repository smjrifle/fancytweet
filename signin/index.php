<?php
session_name('ft');
session_start();
require_once '../twitteroauth.php';
require_once '../config.php';
$_SESSION['stage']=$_SERVER['HTTP_REFERER'];
$auth = new TwitterOAuth($config['key'], $config['secret']);
$request_token = $auth->getRequestToken($config['callback']);
$auth_url = $auth->getAuthorizeURL($request_token);
//save request token in SESSION so that it can be used for getting access token
$_SESSION['request'] = $request_token;
header('Location: ' . $auth_url);
?>
