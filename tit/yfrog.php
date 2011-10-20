<?php

class Yfrog {

//twitteroauth
    private $auth;

    function __construct($auth) {
        $this->auth = $auth;
    }

    function upload($message, $file, $tweet= TRUE) {
        $post = array
            (
            'media' => '@' . $file, // filename
            'message' => ' '.$message
        );
        return $this->processPost($post, $tweet);
    }

    function shareLink($message, $link, $tweet= TRUE) {
        $post = array
            (
            'url' => $link, // url
            'message' => ' '.$message
        );
        return $this->processPost($post, $tweet);
    }

    private function processPost($post, $tweet) {
//    // your app's OAuth consumer & secret
//    define('OAUTH_CONSUMER_KEY', 'L6ndbNvAYkpmdDi52CxvOQ');
//    define('OAUTH_CONSUMER_SECRET', 'rm49aqKOjhNTCCdqGzG7wwwOt2P4zVlRZtObBDKxY');
//
//    // your app user's token and secret, when twitter user granted access to your app
//    define('OAUTH_TOKEN_KEY', '271311461-Lp9wIOlfleHV9YnKzQim6Xse8Z24ZBdeKOify5am');
//    define('OAUTH_TOKEN_SECRET', '5d8OOPmvbyywzY8z4gKJLiOSt5E6pAroltiwsCzyK8o');
//
//    require_once('../OAuth.php');
        // instantiating OAuth customer 
        $consumer = $this->auth->consumer;
        // instantiating signer
        $sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
        // user's token
        $token = $this->auth->token;

        // sorry, guys, I'm too lazy to generate all parameters by myself so I'll parse URL that OAuth lib will construct for me :)
        // signing URL
        $url = 'https://api.twitter.com/1/account/verify_credentials.xml';
        $request = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $url, array());
        $request->sign_request($sha1_method, $consumer, $token);
        $url = $request->to_url();
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $args);
        // OK, we have all data needed in $args
        $ch = curl_init();


        // adding required headers, note: urlencode is important!
        $headers = array
            (
            "X-Auth-Service-Provider: https://api.twitter.com/1/account/verify_credentials.xml",
            "X-Verify-Credentials-Authorization: " .
            "oauth_consumer_key=\"" . urlencode($args['oauth_consumer_key']) . "\", " .
            "oauth_nonce=\"" . urlencode($args['oauth_nonce']) . "\"," .
            "oauth_signature=\"" . urlencode($args['oauth_signature']) . "\"," .
            "oauth_signature_method=\"HMAC-SHA1\", oauth_timestamp=\"" . $args['oauth_timestamp'] . "\"," .
            "oauth_token=\"" . urlencode($args['oauth_token']) . "\"," .
            "oauth_version=\"1.0\""
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'http://yfrog.com/api/xauth_upload');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($ch);
        $response_info = curl_getinfo($ch);
        curl_close($ch);
        $err = (object) 'Error';
        if ($response_info['http_code'] == 200) { //Success
            $response = simplexml_load_string($response);
            if (isset($response->mediaid)) {
                if ($tweet) {
                    //$this->auth->post('statuses/update', array('status' => $post['message'] . ' ' . $response->mediaurl));
                }
            } else if(isset($response->err['code'])){
                $err->code = $response->err['code'];
                $err->message = $response->err['msg'];
                return $err;
            }
            else{
                $err->code=0;
                $err->message = "Yfrong is drunk. Try again later.";
                return $err;
            }
        } else {
            $err->code=$response_info['http_code'];
            $err->message = $response;
            if ($response=='') $err->message = "Yfrong is drunk. Please try again later.";
            return $err;
        }
        return $response;
    }

}

?>