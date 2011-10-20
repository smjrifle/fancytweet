<title>Log In to FancyTweet!</title>
<link rel="stylesheet" type="text/css" href="../base.css" media="screen" />
<script src="form.js" type="text/javascript"></script>
</head>

<body>
    <?php require_once '../header.php'; ?>
    <div id="main">
        <?php
        //if already logged in
        if (isset($user->user_name)) {
            ?>
            You are already logged in as <?php echo $user->user_name; ?><br/>
            <a href="<?php echo $base; ?>/logout">Sign out</a> to login again as different user!

            <?php
        } else if (isset($_POST['submit'])) {//if login details are being received
            $r = $myDB->fetchFirstRow("SELECT user_id FROM auth WHERE user_name='" . $myDB->escape($_POST['username']) . "' AND password='" . md5($_POST['password']) . "'");
            if(isset($r->user_id)){
            $_SESSION['user_id']=$r->user_id;
            //header("Location: " . $_SESSION['stage']);
            echo "<script>window.location=\"" . $_SESSION['stage'] . "\"</script>";
            }else{
                echo "Incorrect Login!";
            }
            //
//echo "<script>window.location=\"" . $_SESSION['stage'] . "\"</script>";
        } else {//if user is neither logged in nor logging in
            //remember to come back to base
            $_SESSION['stage'] = $base;
            //if referred from somewhere remember to go back there
            if (isset($_SERVER['HTTP_REFERER']))
                $_SESSION['stage'] = $_SERVER['HTTP_REFERER'];
            include 'form.php';
        }
        ?>
    </div>
</body>