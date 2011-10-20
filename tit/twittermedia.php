<?php

class TwitterMedia {

    private $auth;

    function __construct($auth) {
        $this->auth = $auth;
    }

    function upload($str,$f) {
        $str="@{$f['full_path']};type=image/jpeg;filename={$f['name']}";
        //echo $str;
        $code = $this->auth->oAuthRequest(
                    'https://upload.twitter.com/1/statuses/update_with_media.json', 'POST', array(
                    'media[]' => "@".$f['full_path'],
                    'status' => 'Picture time',
                        ));
return $code;
                    }

}

?>
