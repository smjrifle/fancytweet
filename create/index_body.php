<link rel="stylesheet" type="text/css" href="../base.css" media="screen" />

</head>

<body>
<?php    require_once '../header.php'; ?>
<div id="main">

    <?php

    if (isset ($user)){
            ?>
    Fill in the form below to create a FancyTweet login for your Twitter Account - <?php echo $user->user_name?>:
    <form action="save.php" method="POST">
        Username :<input type="text" name="username" value="<?php echo $user->user_name?>"disabled /><br/>
        Password: <input type="password" name="pass" /><br/>
        Confirm Password: <input type="password" name="password" /><br/>
        E-Mail Address : <input type="text" name="email" /><br/>
        <input type="submit" value="Go!" name="submit">
    </form>
    <?php
    }
    else
    {
        echo "you are not signed in via Twitter. Sign in with twitter first to create your FancyTweet Login!";
    }


            ?>

     

</div>
    






</body>