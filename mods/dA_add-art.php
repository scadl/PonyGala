<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

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

// Got dA auth code
if (isset($_GET['code'])) {

    $stateText = "Auth code: " . $_GET['code'] . "<br/>";

    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']]),
        ]
    ];
    $context = stream_context_create($opts);

    if ($context) {

        $content = file_get_contents(
                'https://www.deviantart.com/oauth2/token?grant_type=authorization_code&' .
                'client_secret=' . $da_secret . '&client_id=' . $da_client . '&' .
                'redirect_uri=' . $redirect_url . '&code=' . $_GET['code'] . '',
                false, $context);

        if ($content === FALSE) {
            file_get_contents('https://www.deviantart.com/oauth2/revoke');
            $stateText = "Logging out now!" . "<br/>";
        } else {
            $json = $content;
        }
    } else {
        $styleColor = "red";
        $stateText = "Error in dA OAuth2 token";
    }
} else {
    $btnTitle = "Соединиться с dA \nThe Authorization Code Grant";
    $btnAction = "window.location.replace('https://www.deviantart.com/oauth2/authorize?response_type=code&client_id=" . $da_client . "&redirect_uri=" . $redirect_url . "&scope=browse')";
}

// Got dA tokens
if (isset($json)) {

    $obj = json_decode($json, true); // GOT assoc array	

    $stateText .= "Auth token: " . $obj['access_token'] . "<br/>";
    if (isset($obj['refresh_token'])) {
        $stateText .= "Refresh token: " . $obj['refresh_token'] . "<br/>";
    }

    $btnTitle = "Читать ленту с dA \nAsync API request";
    $btnAction = "IniTdAFeed('" . $obj['access_token'] . "')";

    //var_dump(json_decode($json, true));
    
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
        <script src="../libs/functions_add.js"></script>
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
                    <span style='font-size:17pt;'>devianArt Integrator UI</span> <br>
                </span>


                <hr style="width: 300px">
                <br>

                <div>
                    <b>Статус: </b>	
                    <div id='daStatus'>
                    </div>	

                    <span id="status">

                        <span style='color:<?php print($styleColor) ?>; font-weight:bold;'>
                            <?php print($stateText) ?>
                        </span> 
                        <br/>

                    </span>
                </div>

                <input type="button" id="loadBtn"
                value="<?php print($btnTitle) ?>" onclick="<?php print($btnAction); ?>" 
                style="white-space: normal; width: 150px;" />         

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
