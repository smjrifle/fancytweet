<div id="header">
    <div id="banner"><strong>FancyTweet</strong>
    </div>
    <div id="auth">
        <?php
        if (isset($user)){
            echo "Signed in as @".$user->user_name;
            if (!($user->password)) echo '<br/><a href="'.$base.'/create/">Create Twitterax Login!</a>';
            echo '<br/><a href="'.$base.'/logout/">Sign Out!</a>';
        }
        else{
?>
        <a href="<?php echo $base?>/signin">Sign In with Twitter</a>
        <br/>
        <a href="<?php echo $base?>/login">Login with FancyTweet Password</a>

<?php
        }
        ?>
    </div>
    <br/>
<hr/>
</div>