<link rel="stylesheet" type="text/css" href="../base.css" media="screen" />
<title>Twitter Image Transloader - FancyTweet</title>
<script type="text/javascript">
    function enableFile(){
        document.getElementById('radio_url').checked=false;
        document.getElementById('radio_file').checked=true;
    }
    function enableURL(){
        document.getElementById('radio_file').checked=false;
        document.getElementById('radio_url').checked=true;
    }
    function checkCheckbox(ele){
        boxes = getElementsByClass('service');
        var f=0;
        for(var i=0; i<boxes.length; i++){
            if(boxes[i].checked) f=1;
        }
        if (f==0) ele.checked=true;
    }

    function getElementsByClass(className,tagName){
        //if tagName is not passed, process all the tags
        tagName = typeof(tagName) != 'undefined' ? tagName : '*';
        //create result array
        var result=new Array();
        //create a counter
        var c=0;
        //get required tags
        var tags=document.getElementsByTagName(tagName);
        //iterate through each tag
        for (var i = 0; i < tags.length; i++) {
            //splitting to check tags with multiple classes
            var classes = tags[i].className.split(' ');
            //iterate through each class and check
            for (var j=0;j<classes.length;j++){
                if (classes[j] == className)   {
                    //save it and increase the counter
                    result[c++]=tags[i];
                }
            }
        }
        //return the array
        return result;
    }
</script>
</head>

<body>
    <?php
    require_once '../header.php';

    function linkify($a) {
        $b = $a;
        if (!(substr($a, 0, 5) == 'http:'))
            $b = 'http://' . $a;
        return '<a target="_blank" href="' . $b . '">' . $a . '</a>';
    }

    function format_size($size) {
        $sizes = array(" Bytes", " KB", " MB", " GB");
        if ($size == 0) {
            return(0);
        } else {
            return (round($size / pow(1024, ($i = floor(log($size, 1024)))), $i > 1 ? 2 : 0) . $sizes[$i]);
        }
    }

    function downFile($in, $out = '') {
        global $file;
        //If second argument is not passed, find the original file name
        if (!$out) {
            $tmp = preg_split('/\//', $in);
            if (!( $file['out'] = $tmp[count($tmp) - 1] ))
                $file['out'] = $tmp[count($tmp) - 2];
        }
        $file['name'] = $file['out'];

        $file['d'] = 'images/' . date('y-m') . '/';
        //create directory <year>-<month> if nor exists
        if (!file_exists($file['d']))
            mkdir($file['d'], 0777);

        //force the file to be created in the images folder
        //$file['out'] = date_timestamp_get(new DateTime()) . '-' . $file['out'];
        $file['out'] = time() . '-' . $file['out'];
        $file['full_path'] = $file['d'] . $file['out'];
        ////create a new CURL connection
        $ch = curl_init(); // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $in);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        set_time_limit(300); // 5 minutes for PHP
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // and also for CURL
        $outfile = fopen($file['full_path'], 'wb');
        curl_setopt($ch, CURLOPT_FILE, $outfile); // grab file from URL
        curl_exec($ch);
        fclose($outfile); // close CURL resource, and free up system resources
        curl_close($ch);
        echo 'From : ' . linkify($in) . ' </br>';
        echo 'File Size: ' . format_size(filesize($file['full_path'])) . '<br/>';
        $file['type'] = mime_content_type($file['full_path']);
        $type = explode('/', $file['type']);
        echo 'File Type: ' . ucwords($type[0]) . '<br/>';
        echo 'File Extension: ' . $type[1] . '<br/>';

        $f = 1;
        //prevent uploading bigger than 10MB
        if (filesize($file['full_path']) > 10485760) {
            echo "<br/>Error :  You can't upload a file bigger than 10 MB!<br/>";
            $f = 0;
        }

        if (!($type[1] == 'jpeg' || $type[1] == 'png' || $type[1] == 'gif' || $type[1] == 'x-jpeg' || $type[1] == 'x-gif' || $type[1] == 'x-png' )) {
            echo "<br/>Error :  Only JPEG, PNG or GIF files are allowed!<br/>";
            $f = 0;
        }

        if ($f) {
            return $file;
        } else {
            echo "Failed! There was an error with the file";
        }
    }

    function showYfrogInfo($response) {

        if (isset($response->scalar)) {
            $str = "<b>Error</b>";
            if ($response->code)
                $str.=" " . $response->code;
            if ($response->message)
                $str.=" - " . $response->message;
            echo $str;
        }
        else if (isset($response['stat'])) {
            echo "Uploaded to " . linkify($response->mediaurl);
        }
        echo "<br><br>";
    }

    function showTwitpicInfo($response) {
        /*
          global $file;
          if (isset($_POST['radio_file'])) {
          $src = 'file';
          $file['d'] = NULL;
          $file['out'] = $_FILES['upload']['name'];
          } else {
          $src = 'url';
          }

          }

         */
        if (isset($response->url)) {
            echo '<br><b>Uploaded to TwitPic by <a href="http://twitter.com/' . $response->user->screen_name . '">@' . $response->user->screen_name . '</a></b><br>';
            echo "TwitPic URL : " . linkify($response->url) . "<br/>";
            echo "Caption : " . $response->text . "<br/>";
            echo "Uploaded on : " . substr($response->timestamp, 0, 17) . "<br>";
            echo "Dimensions : " . $response->width . " x " . $response->height;
        } else {
            echo "Error " . $response->code . " : " . "" . $response->message;
        }
        echo "<br><br>";
    }
    ?>
    <div id="main">
        <?php
        $gu = '';
        $gt = '';
        if (isset($user)) {

            date_default_timezone_set('GMT');
            $file = array();

            //get url passed as GET param
            //otherwise from session saved before signing in

            if (isset($_SESSION['gu'])) {

                if ($_SESSION['gu'] != '')
                    $gu = $_SESSION['gu'];
                //clear it once retrieved
                //$_SESSION['gu']='';
            }

            if (isset($_SESSION['gt'])) {
                if ($_SESSION['gt'] != '')
                    $gt = $_SESSION['gt'];
                //clear it once retrieved
                //$_SESSION['gt']='';
            }

            //if passed as GET param
            if (isset($_GET['url'])) {
                $gu = $_GET['url'];
            }
            //if passed as GET param
            if (isset($_GET['title'])) {
                $gt = $_GET['title'];
            }

            if (isset($_POST['submit'])) {

                require_once '../twitteroauth.php';


                $message = $_POST['message'];

                $auth = new TwitterOAuth($config['key'], $config['secret'], $user->oauth_token, $user->oauth_token_secret);

                if (isset($_POST['radio_file'])) {
                    $fl = $_FILES['upload'];

                    $f = 1;

                    switch ($fl['error']) {
                        //   case 0:
                        //     echo "File successfully Uploaded!<br/>";
                        //     break;
                        case 2:
                            echo "<br/>Error :  You can't upload a file bigger than 10 MB!<br/>";
                            $f = 0;
                            break;
                        case 3:
                            echo "<br/>Error : The upload didn't complete!!<br/>";
                            $f = 0;
                            break;
                        case 4:
                            echo "<br/>Error : No files were selected for uploading!<br/>";
                            $f = 0;
                            break;
                        case 7:
                            echo "<br/>Error : Writing file to the disk failed!<br/>";
                            $f = 0;
                            break;
                    }

                    $type = explode('/', $fl['type']);
                    echo 'File Size: ' . format_size($fl['size']) . '<br/>';
                    if (count($type) == 2) {

                        echo 'File Type: ' . ucwords($type[0]) . '<br/>';
                        echo 'File Extension: ' . $type[1] . '<br/>';
                        if (!($type[1] == 'jpeg' || $type[1] == 'png' || $type[1] == 'gif' )) {
                            echo "<br/>Error :  Only JPEG, PNG or GIF files are allowed!<br/>";
                            $f = 0;
                        }
                    } else {
                        echo "<br/>Error :  Unidentified File Type!<br/>";
                        $f = 0;
                    }
                }

                if (in_array('twitpic', $_POST['service'])) {
                    require_once 'twitpic.php';
                    $twitpic = new Twitpic($auth);

                    if (isset($_POST['radio_url'])) {

                        $file = downFile($_POST['file']);
                        $response = $twitpic->upload($message, $file['full_path'], false);
                        $url = $response->url;
                        showTwitpicInfo($response);
                        //show info
                    } else if (isset($_POST['radio_file'])) {
                        if ($f) {//no error
                            $response = $twitpic->upload($message, $fl['tmp_name'], false);
                            $url = $response->url;
                            showTwitpicInfo($response);
                        }
                    }
                }
                if (in_array('yfrog', $_POST['service'])) {
                    require_once 'yfrog.php';
                    $yfrog = new Yfrog($auth);

                    if (isset($_POST['radio_url'])) {
                        $response = $yfrog->shareLink($message, $_POST['file'], false);
                        $url = $response->mediaurl;
                        showYFrogInfo($response);
                    } else if (isset($_POST['radio_file'])) {
                        if ($f) {//no error
                            $response = $yfrog->upload($message, $fl['tmp_name'], false);
                            $url = $response->mediaurl;
                            showYFrogInfo($response);
                        }
                    }
                }

                if (isset($_POST['tweet'])) {
                    $r = $auth->post('statuses/update', array('status' => $message . ' ' . $url));
                    if (isset($r->error))
                        echo "Error: " . $r->error;
                    else
                        echo "Status Updated!";
                }
            }
            ?>

            <form enctype="multipart/form-data" method="POST" action="">
                <input class="service" type="checkbox" name="service[]" value="twitpic" onclick="checkCheckbox(this)" checked>TwitPic
                <input class="service" type="checkbox" name="service[]" value="yfrog" onclick="checkCheckbox(this)">yfrog
                <!--
                <input class="service" type="checkbox" name="service" value="twittermedia" onclick="checkCheckbox(this)">Twitter
                -->
                <br><br><input type="radio" id="radio_url" title="Upload via URL!" onclick="enableURL();" name="radio_url" checked>
                <label for="radio_url">URL : </label>
                <input id="url" type="text" name="file" onfocus="enableURL();" size="50" value="<?php echo $gu; ?>"/><br />
                <b>OR</b><br/>
                <input type="radio" id="radio_file" title="Upload Image file!" onclick="enableFile();" name="radio_file">
                <label for="radio_file">File : </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                <input id="file" type="file" name="upload" onfocus="enableFile();" size="50"/><br /><br />
                Caption : <input type="text" name="message" size="50" value="<?php echo $gt; ?>"/><br /><br />
                <input type="checkbox" name="tweet" value="tweet" checked> Tweet
                <input type="submit" name="submit" value="GO!" />
            </form>
            <?php
        } else {
            echo "You are not signed in via Twitter. Sign in with twitter first to start TwitPiccing from URL!";
            //if url is passed as GET, save it on session
            if (isset($_GET['url'])) {

                $_SESSION['gu'] = $_GET['url'];
                $_SESSION['gt'] = $_GET['title'];
            }
        }
        ?>
    </div>

</body>