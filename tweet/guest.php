<?php
require_once '../config.php';
                ?><a href="<?php echo $base ?>/signin">Sign In with Twitter</a><br/>
                Or use FancyTweet login:
<br/>
        Username: <input type="text" id="username" name="username" onkeyup="vaildateField(this,0);" onblur="vaildateField(this,1);"/>
        <span style="display: none;" id="valusername"></span>
        <br/>
        Password: <input type="password" id="password" name="password" onkeyup="vaildateField(this,0);" onblur="vaildateField(this,1);"/>
        <span style="display: none;" id="valpassword"></span>
        <br/>
        
