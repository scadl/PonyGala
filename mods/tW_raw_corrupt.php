<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
}

require '../preinit.php';
require '../db_init.php';

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

    $rq_url = 'https://api.twitter.com/oauth/request_token';

    $key = $tw_api_secret.'&';

    $tw_time = time();
    $tw_nonce = md5(microtime() . mt_rand());

    $rq_data= array(
        'oauth_nonce' => $tw_nonce,
        'oauth_callback' => $redirect_url,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp' => $tw_time,
        'oauth_consumer_key' => $tw_api_key,
        'oauth_version' => "1.0"
    );

    $sig_data = $rq_data;
    ksort($sig_data, SORT_STRING);

    $signatureBase = "POST&".urlencode($rq_url)."&---";
    foreach($sig_data as $sig_key => $sig_val){
        $signatureBase .= urlencode($sig_key .'='. $sig_val .'&');
    }
    //print($signatureBase);

    $tw_sig = base64_encode(hash_hmac('sha1', $signatureBase, $key, true));
   
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => implode("\n", [
                'Authorization: OAuth '.
                    'oauth_nonce="'.$tw_nonce.'", '.
                    'oauth_callback="'.urlencode($redirect_url).'", '.
                    'oauth_signature_method="HMAC-SHA1", '.
                    'oauth_timestamp="'.$tw_time.'", '.
                    'oauth_consumer_key="'.$tw_api_key.'", '.
                    'oauth_signature="'.$tw_sig.'", '.
                    'oauth_version="1.0"'
                ]),
        ]
    ];
    $context = stream_context_create($opts);
    //print_r($opts);




    // Curl way
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $rq_url);
    curl_setopt($ch, CURLOPT_POST, 1);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $headers = [
        'Authorization: OAuth '.
        'oauth_nonce="'.$tw_nonce.'", '.
        'oauth_callback="'.urlencode($redirect_url).'", '.
        'oauth_signature_method="HMAC-SHA1", '.
        'oauth_timestamp="'.$tw_time.'", '.
        'oauth_consumer_key="'.$tw_api_key.'", '.
        'oauth_signature="'.$tw_sig.'", '.
        'oauth_version="1.0"'
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $server_output = curl_exec ($ch);
    
    curl_close ($ch);
    
    print  $server_output ;





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
