<?php

class Twitpic {
    
    //twitteroauth
    private $auth;
    
    function __construct($auth) {
        $this->auth=$auth;
    }
    
       function upload($message, $file, $tweet= TRUE) {
        //Has the user submitted an image and message?

        $twitpicURL = 'http://api.twitpic.com/2/upload.json';

        //Set the initial headers
        $header = array(
            'X-Auth-Service-Provider: https://api.twitter.com/1/account/verify_credentials.json',
            'X-Verify-Credentials-Authorization: OAuth realm="http://api.twitter.com/"'
        );


        // instantiating OAuth customer
        $consumer = $this->auth->consumer;

        // instantiating signer
        $sha1_method = new OAuthSignatureMethod_HMAC_SHA1();

        // user's token
        // list($oauth_token, $oauth_token_secret) = explode('|', $GLOBALS['user']['password']);
        $token = $this->auth->token;

        // Generate all the OAuth parameters needed
        $signingURL = 'https://api.twitter.com/1/account/verify_credentials.json';
        $request = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $signingURL, array());
        $request->sign_request($sha1_method, $consumer, $token);

        $header[1] .= ", oauth_consumer_key=\"" . $request->get_parameter('oauth_consumer_key') . "\"";
        $header[1] .= ", oauth_signature_method=\"" . $request->get_parameter('oauth_signature_method') . "\"";
        $header[1] .= ", oauth_token=\"" . $request->get_parameter('oauth_token') . "\"";
        $header[1] .= ", oauth_timestamp=\"" . $request->get_parameter('oauth_timestamp') . "\"";
        $header[1] .= ", oauth_nonce=\"" . $request->get_parameter('oauth_nonce') . "\"";
        $header[1] .= ", oauth_version=\"" . $request->get_parameter('oauth_version') . "\"";
        $header[1] .= ", oauth_signature=\"" . urlencode($request->get_parameter('oauth_signature')) . "\"";

        //open connection
        $ch = curl_init();

        //Set paramaters
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $twitpicURL);

        //TwitPic requires the data to be sent as POST
        $media_data = array(
            'media' => '@'.$file,
            'message' => ' ' . $message, //A space is needed because twitpic b0rks if first char is an @
            'key' => 'fa2fdecae1770800f98a923ac7526e97'
        );

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $media_data);

        //execute post
        $result = json_decode(curl_exec($ch));
        $response_info = curl_getinfo($ch);


        //close connection
        curl_close($ch);
        if ($response_info['http_code'] == 200) { //Success
            if ($tweet) {
                //Decode the response
                //if (gettype($result)!='object')
                //$result = json_decode($result);
                //$id = $result->id;
                //the status pattern
                $status = $result->text . ' ' . $result->url;
                //$this->auth->post('statuses/update', array('status' => $status));
            }
        } else {

//            $content = "<p>Twitpic upload failed. No idea why!</p>";
//            $content .= "<pre>";
//            $json = json_decode($result);
//            //$content .= "<br / ><b>message</b> " . urlencode($_POST['message']);
//            $content .= "<br / ><b>json</b> " . print_r($json);
//            $content .= "<br / ><b>Response</b> " . print_r($response_info);
//            $content .= "<br / ><b>header</b> " . print_r($header);
//            $content .= "<br / ><b>media_data</b> " . print_r($media_data);
//            $content .= "<br /><b>URL was</b> " . $twitpicURL;
//            //$content .= "<br /><b>File uploaded was</b> " . $_FILES['media']['tmp_name'];
//            $content .= "</pre>";
            //$json = json_decode($result);
            //print_r($json);
            //print_r($response_info);
            //print_r($header);

            $err = (object) 'Error';
            $err->code = $response_info['http_code'];
            $err->message = 'Twitpic is drunk. Please, try again later. OR Am I?';
            return $err;
        }
        return $result;
    }

 
    
}

?>
