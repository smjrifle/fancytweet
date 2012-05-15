<link rel="stylesheet" type="text/css" href="base.css" media="screen" />
<title>FancyTweet Home</title>
<script type="text/javascript" src="base.js"> </script>
</head>

<body>
    <?php require_once 'header.php'; ?>
    <?php require_once 'sidebar.php'; ?>
    <div id="main">
        <?php
        if (isset($_POST['tweet'])) {
            if (isset($_SESSION['user_id'])) {
                require_once 'config.php';
                require_once 'twitteroauth.php';
                require_once 'database.php';
                $user = $myDB->fetchFirstRow("SELECT * FROM auth WHERE user_id=" . $_SESSION['user_id'] . "");
                $auth = new TwitterOAuth($config['key'], $config['secret'], $user->oauth_token, $user->oauth_token_secret);
                $r = $auth->post('statuses/update', array('status' => html_entity_decode($_POST['outputText'], ENT_NOQUOTES, 'UTF-8')));
                if (isset($r->error))
                    echo "<div style='color:red'>Error: " . $r->error."</div>";
                else
                    echo "<div style='color:green'>Status Updated!</div>";
            } else {
                echo 'You need to <a href="/signin">login</a> in order to tweet from here.';
            }
//print_r($auth);
        }
        ?>
        <form name="inputForm" onkeyup="transformText();" action="/" method="POST">
            <br>
            <font color="#006699" face="Verdana, Geneva, sans-serif">Input<span id="inputCount" style=""></span>:</font>

            <br>
            <textarea cols="55" id="input" rows="6" name="inputText"></textarea>
            <!--The transformation options begin here-->
            <!--The first set of options is for translations-->
            <div class="options">
                <input name="layer1" id="piratify" onclick="transformText();" title="Translate into Pirate language!" type="radio"><label for="piratify">Piratify!</label>
                <input name="layer1" id="jumble" onclick="transformText();" title="Get Jumbled Words!" type="radio"><label for="jumble">Jumble!</label>
                <input name="layer1" id="shrink" onclick="transformText();" title="Shrink words!" type="radio"><label for="shrink">Shrink!</label>
            </div>
            <!--The second set of options is for flipping-->
            <div class="options"><input name="layer1" id="flip" onclick="transformText();" title="Flip characters in text!" type="radio" checked><label for="flip"> Flip :</label>
                <input name="layer2" id="down" onclick="transformText();" title="Turn letters upside-down!" type="checkbox" checked><label for="down">Upside-Down</label>
                <input name="layer2" id="back" onclick="transformText();" title="Revert text backwards!" type="checkbox" checked><label for="back">Revert Backwards</label>
            </div>
            <!--The third set of options allows character effects-->
            <div class="options">
                <input name="layer1" id="greekify" onclick="transformText();" title="Use Greek alphabets!" type="radio"><label for="greekify">Greekify</label>
                <input name="layer1" id="leet" onclick="transformText();" title="Translate into elite speak!" type="radio"><label for="leet">L33t</label>
                <input name="layer1" id="crazy" onclick="transformText();" title="Use weird characters!" type="radio"><label for="crazy">Crazy</label>
                <input name="layer1" id="diacritic" onclick="transformText();" title="Use characters with glyphes!" type="radio"><label for="diacritic">Diacritics</label>
                <input name="layer1" id="bubble" onclick="transformText();" title="Use characters enclosed in circle!" type="radio"><label for="bubble">Bubble-Text</label>
            </div>
            <br>
            <!--Division for output and buttons-->
            <div id="depends" style="display:none;"><font color="#006699" face="Verdana, Geneva, sans-serif">Output<span id="outputCount"></span>:</font>
                <br>
                <textarea id="output" cols="55" rows="6" name="outputText" readonly="readonly"> </textarea>
                <!--Buttons for in-page activities like selecting, clearing-->
                <div id="local">
                    <input name="selectAll" value="Select All" type="button" onclick="$('output').select();">
                    <input name="clear" value="Clear" onclick="document.inputForm.reset();" type="button"></div>
                <input type="submit" name="tweet" value="Tweet!"/>
            </div>
        </form>
        <div id="right-bar" style="float: right;width: 200px;">
            <div id="help-content">Help goes here</div>
        </div>
    </div>            

</div>
</body>
