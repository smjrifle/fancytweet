<link rel="stylesheet" type="text/css" href="../base.css" media="screen" />
<title>BackUp Tweets - TwitterAx</title>

</head>

<body>
    <?php
    require_once '../header.php';
    $user_name=(isset($user->user_name))?$user->user_name:'';
    ?>
    <form action ="download.php" method="POST">
<input type="text" name="username" value="<?php echo $user_name?>"/>
<input type="submit" value="Download"/>
       
       </form>
       
</body>