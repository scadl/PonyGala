<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
}

require '../preinit.php';
require '../db_init.php';

// https://github.com/abraham/twitteroauth
require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', $tw_api_key);
define('CONSUMER_SECRET', $tw_api_secret);
define('OAUTH_CALLBACK', $redirect_url_t);

// --------- Main server code -------
$styleColor = "grey";
$stateText = "";
$lastsel = "";
$btnTitle = "";
$btnAction = "";
$list_opt = "";
$form_vis = "none";
$btn_vis = "normal";
$loaded_data = "";

// Twitter OAuth 1.0 method (3-legged OAuth)
// https://developer.twitter.com/en/docs/authentication/oauth-1-0a/obtaining-user-access-tokens

if(!isset($_SESSION['access_token'])) {
    // Step I: Obtain a request token

    $libApiConn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $req_token = $libApiConn->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    
    print_r($req_token);

    if ($req_token['oauth_callback_confirmed']) {
        $styleColor = "green";
        $stateText = "<br>Twitter OAuth 1.0 Request Token granted<br>
        <span style='font-weight:normal'>[".$req_token['oauth_token']."]</span><br>";
    } else {
        $styleColor = "red";
        $stateText = "Error in Twitter OAuth 1.0 connection!";
    }

    $_SESSION['oauth_token'] = $req_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $req_token['oauth_token_secret'];

    // Step II: send the consumer application a request token
    $url_auth = $libApiConn->url('oauth/authorize', array('oauth_token' => $req_token['oauth_token']));

    $btnTitle = "Соединиться с Twitter \nOAuth 1.0 Authorize";
    $btnAction = "window.location.replace('".$url_auth."')";
 
} elseif (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
    // Step II: send the consumer application a request token

    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

    if (isset($_GET['oauth_token']) && $request_token['oauth_token'] !== $_GET['oauth_token']) {        
        $styleColor = "red";
        $stateText = "Error in Twitter OAuth 1.0 Authorization!";
    } else {
        $styleColor = "green";
        $stateText = "<br>Twitter OAuth 1.0 Auth Token & Verifier granted<br>
        <span style='font-weight:normal'>[".$_GET['oauth_token']."]</span><br>
        <span style='font-weight:normal'>[".$_GET['oauth_verifier']."]</span><br>";
    }

    // Step III: Convert the request token into a usable access token.
    $libApiConn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, 
        $request_token['oauth_token'], $request_token['oauth_token_secret']);

    try{    
        $acc_token = $libApiConn->oauth("oauth/access_token", ["oauth_verifier" => $_GET['oauth_verifier']]);
        $_SESSION['access_token'] = $acc_token;
        print_r($acc_token);
    } catch(Exception  $e) {
        header('Location: ../mods/tW_add-art.php');
    }
    
} 
if(isset($_SESSION['access_token'])) {

    $form_vis = "normal";
    $btn_vis = "none";

    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $lists = $connection->get("lists/list", ['reverse' => true]);
    foreach($lists as $list){
        $list_opt .= "<option value='".$list->id."'>".$list->name."</option>";        
    }
    
    //print_r($access_token);
}

if(isset($_SESSION['access_token']) && isset($_GET['list'])){

    //$form_vis = "none";
    $btn_vis = "none";

    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    $statuses = $connection->get("lists/statuses", ["list_id" => $_GET['list'], "include_rts" => false]);

    foreach($statuses as $tweet){

        $user = $tweet->user->name;
        if (isset($tweet->extended_entities)){
        $media = $tweet->extended_entities->media;
        foreach($media as $media_el){
            if($media_el->type == "photo"){
                $art_name = "Twitter N ".substr($media_el->id, 0, 7);
                $loaded_data .= "<tr>
                <td><img src='".$media_el->media_url."?name=thumb'/></td>
                <td><a href='".$media_el->url."' target='_blank'> ".$art_name."</td>
                <td>".$user."</td>
                </tr>";
                }
            }
        }
        
    }

    print_r($access_token);
    //print("<pre>"); print_r($statuses); print("</pre>");


}

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>PonyArt Gallery (by scadl) [dA Import]</title>
        <link rel="stylesheet" href="../libs/admin_forms.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.9.1.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        
        <style type="text/css">
                .pvImg{
                    cursor:default;
                }
            </style>
        </head>
        <body>

            <div align="center">

                <span style='font-family:CelestiaRedux;'>
                    <span style='font-size:45pt;'>Big DigitalArt Gallery</span><br>
                    <span style='font-size:17pt;'>Twitter Integrator UI</span> <br>
                </span>


                <hr style="width: 300px">
                <br>

                <div>
                    <b>Статус: </b>	
                    <div id='daStatus'></div>	

                    <span id="status">

                        <span style='color:<?php print($styleColor) ?>; font-weight:bold;'>
                            <?php print($stateText) ?>
                        </span> 
                        <br/>

                    </span>
                </div>

                <form action="" method="get" style="display:<?php print($form_vis); ?>">
                    <select name="list"><?php print($list_opt); ?></select>
                    <input type="submit" value="Read it!">
                </form>

                <input type="button" style="white-space: normal; width: 150px; display:<?php print($btn_vis); ?>"
                value="<?php print($btnTitle) ?>" onclick="<?php print($btnAction); ?>" />         

                <table id="log"><?php print($loaded_data); ?></table>	

                <br>
                <span style="color:grey; font-size:7pt;">	
                    Scripting and Design: <b>scadl</b><br>
                    <i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
                </span>

            </div>

        </body>
    </html>
