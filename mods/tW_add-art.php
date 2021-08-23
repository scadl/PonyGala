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

// --------- Main server code -------
$styleColor = "grey";
$stateText = "";
$lastsel = "";
$btnTitle = "";
$btnAction = "";

// Twitter OAuth 1.0 method (3-legged OAuth)
// https://developer.twitter.com/en/docs/authentication/oauth-1-0a/obtaining-user-access-tokens

if (isset($_GET['oauth_token'])) {
    // Step II: send the consumer application a request token

} elseif (isset($_GET['oauth_verifier'])){
    // Step III: Convert the request token into a usable access token.

} else {
    // Step I: Obtain a request token

    define('CONSUMER_KEY', $tw_api_key);
    define('CONSUMER_SECRET', $tw_api_secret);
    define('OAUTH_CALLBACK', $redirect_url);
    $libApiConn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $req_token = $libApiConn->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    
    //print_r($req_token);

    if ($context) {

        $content = file_get_contents($rq_url, false, $context);

        $stateText = $content;

        if ($content === FALSE) {
            //file_get_contents('https://www.deviantart.com/oauth2/revoke');
            //$stateText = "Logging out now!" . "<br/>";
        } else {
            $json = $content;
        }
    } else {
        $styleColor = "red";
        $stateText = "Error in Twitter OAuth 1.0 token";
    }
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


                <input type="button" value="<?php print($btnTitle) ?>" onclick="<?php print($btnAction); ?>" style="white-space: normal; width: 150px;" />         

                <table id="log">                    
                </table>	

                <br>
                <span style="color:grey; font-size:7pt;">	
                    Scripting and Design: <b>scadl</b><br>
                    <i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
                </span>

            </div>

        </body>
    </html>
