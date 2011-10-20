<?php
$user = $myDB->fetchFirstRow("SELECT * FROM auth WHERE user_id=" . $_SESSION['user_id'] . "");
echo "Signed in as @" . $user->user_name."<br/>";
if (!($user->password))
    echo '<a href="' . $base . '/create/">Create FancyTweet Login for your twitter account!</a><br/>';
echo '<a href="' . $base . '/logout/">Sign Out!</a><br/>';
?>