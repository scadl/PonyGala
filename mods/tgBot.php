

<?php
require '../preinit.php';
require '../db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// About Telegrma bot system
// https://core.telegram.org/bots                   -- Get started and Demo
// https://core.telegram.org/bots/api               -- All API
// https://core.telegram.org/bots/faq               -- Bot FAQ
// https://core.telegram.org/bots/samples/hellobot  -- Simple bot

define('BOT_TOKEN', $tg_bot_secret);
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');


function simpleRequest($method, $params){
        
    //print(API_URL."<br>");

    // Get the messages sended to bot throught the last 24 hours
    // getUpdates is a poll-type method (https://core.telegram.org/bots/api#getupdates)
    $params["method"] = $method;
    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 300);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);                // Curl error 60, SSL certificate issue: self signed certificate in certificate chain
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        
    //print($handle."<br>");
    

    //$httpCode = curl_getinfo($handle , CURLINFO_HTTP_CODE); // this results 0 every time
    $response = curl_exec($handle);

    //if ($response === false) 
    //    $response = curl_error($handle);

    //echo 'Error: ' . stripslashes($response);

    // An 'Update' object (https://core.telegram.org/bots/api#update)
    // Carry all data about users activity, captured by bot
    return json_decode($response, false);
}

function telegram_emoji($utf8emoji) {
    preg_replace_callback(
        '@\\\x([0-9a-fA-F]{2})@x',
        function ($captures) {
            return chr(hexdec($captures[1]));
        },
        $utf8emoji
    );

    return $utf8emoji;
}

function sendResponseWithArt($artCatID, $tgChatID){

    global $link;
    $artOk = false;

    // Try to send image until it actualy loaded from source
    while(!$artOk){
        $rq = "SELECT * FROM arts_pub WHERE category=".$artCatID." ORDER BY rand() LIMIT 1";
        $sql = mysqli_query($link, $rq);
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {

            $resultPh = simpleRequest(
            'sendPhoto', 
                array(
                    'chat_id' => $tgChatID,
                    'parse_mode' => 'MarkdownV2',
                    'photo' => $row['file_name'],
                    'caption' => '[*'.$row['title'].'*]('.$row['da_page'].')' . ' by _'.$row['author'].'_'
                )
            );

            // Ask Telegram, if image sent wtih provided link.
            // Has to relay on telegram because of connection problems in my region
            $artOk = $resultPh->ok;
            
            // Debug - send respones body
            // simpleRequest('sendMessage', array('chat_id' => $tgChatID, 'text' => $resultPh));
        }
    }

    simpleRequest(
        'sendMessage', 
        array(
            'chat_id' => $tgChatID,
            'parse_mode' => 'MarkdownV2',
            'text' => 'Это случайный арт из выбарнной категории\.'.PHP_EOL.'Тебе понравилось\?',
            'reply_markup' => array(
                'keyboard' => array(array(' Отличный арт', 'Не не пойдет')),
                'one_time_keyboard' => true,
                'resize_keyboard'=> true
            )
        )
    );
}

function sendLastSelection($tgChatID, $categID, $categName){

    global $link;

    

    $publication = '';
    $sqlp = mysqli_query($link, "SELECT `file_name`, addate FROM arts_pub GROUP BY addate ORDER BY STR_TO_DATE(addate, '%e-%m-%Y') DESC LIMIT 1");                
    while ($rowp = mysqli_fetch_array($sqlp, MYSQLI_ASSOC)) {
        $publication = $rowp['addate'];                
    } 

    $artOk = false;
    while(!$artOk){

        $images = array();
        $rq = "SELECT * FROM arts_pub WHERE category=".$categID." AND addate='".$publication."' ORDER BY rand() LIMIT 5";
        $sql = mysqli_query($link, $rq);
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {

            $images[] = array(
                'type'=>'photo',
                'media'=> $row['file_name'],
                'caption'=> '"'.$row['title'].'" by '.$row['author']
            );

        }


        $resultPh = simpleRequest(
            'sendMediaGroup', 
            array(
                'chat_id' => $tgChatID,
                'media' => json_encode($images)
            )
        );
        
        $artOk = $resultPh->ok;
    }

    $date = new DateTime($publication);
    simpleRequest(
        'sendMessage', 
        array(
            'chat_id' => $tgChatID,
            'parse_mode' => 'MarkdownV2',
            'text' => 'Это 5 артов из категории '.PHP_EOL.'"'.$categName.'" от '.date_format($date, "d M Y").'\.'.PHP_EOL.'Тебе понравилось\?',
            'reply_markup' => array(
                'keyboard' => array(array('Да, давай еще!', 'Не, давай другие')),
                'one_time_keyboard' => true,
                'resize_keyboard'=> true
            )
        )
    );
}

function categorySystem($prefix, $sendMode=false, $usrMsg="", $chatID=""){

    global $link;

    $categories = array();
    $categ_ids = array();
    $cat_num=0;

        $rq = "SELECT * FROM categories WHERE cat_id<>1";
        $sql = mysqli_query($link, $rq);
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
            $categories[] = $row['cat_name'];   
            $categ_ids[] = $row['cat_id'];
            $cat_num++;     
        }

        for($i=0; $i<$cat_num; $i+=2){
            $cat_format[] = array(
                $prefix .' '. $categories[$i], 
                $prefix .' '. $categories[$i+1]
            );
        }

        $cat_buttons = array(
            'keyboard' => $cat_format,
            'one_time_keyboard' => true,
            'resize_keyboard'=> true
        );

        if($sendMode){
            foreach($categories as $cKey => $cVal){
                if(strpos($usrMsg, $cVal) > 0){
                    if($prefix == "[К]"){
                        sendResponseWithArt($categ_ids[$cKey], $chatID);
                    } elseif ($prefix == "[П]"){
                        sendLastSelection($chatID, $categ_ids[$cKey], $cVal);
                    }
                }
            }
        }

        return $cat_buttons;
}


function webhookProcess($response){

    global $link;
    $val = $response;
    
    if(property_exists($val, 'message')){
        
        print_r($val->update_id .' '. $val->message->text.'<br>');

        // Send a response to user, only if this message is new
        //if(isset($_COOKIE['tg_last_update_id']) && $val->update_id > $_COOKIE['tg_last_update_id']){

            if (strpos($val->message->text, "/start") === 0 OR strpos($val->message->text, "Не, давай другие") === 0) {
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => "Привет! Какие арты тебе нравятся?",
                    'reply_markup' => categorySystem("[К]")
                ));

                 // Debug - send respones body
                //simpleRequest('sendMessage', array('chat_id' => $val->message->chat->id, 'text' => $resultPh  ));
            }
            if (strpos($val->message->text, "/last") === 0 OR strpos($val->message->text, "Да, давай еще!") === 0 ) {
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => "Привет! Какие арты тебе нравятся?",
                    'reply_markup' => categorySystem("[П]")
                ));
            }
            if(strpos($val->message->text, "[К]") === 0){
                categorySystem("[К]", true, $val->message->text, $val->message->chat->id);
            }
            if(strpos($val->message->text, "[П]") === 0){
                categorySystem("[П]", true, $val->message->text, $val->message->chat->id);
            }

            if(strpos($val->message->text, "Отличный арт") === 0){
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => 'Мы рады что тебе понравилось.'.PHP_EOL.'Может быть хочешь еще один? '.PHP_EOL.'Выбирай категорию:',
                    'reply_markup' => categorySystem("[К]")
                ));
            }

            if(strpos($val->message->text, "Не не пойдет") === 0){
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => 'Нам жаль что тебе не нравится наш выбор. '.PHP_EOL.'Давай попробуем другую категорию:',
                    'reply_markup' => categorySystem("[К]")
                ));
            }

            if(strpos($val->message->text, "/help") === 0){
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => 'Наш бот пока умеет немного: '.PHP_EOL.
                        '/start - Перезапуск и выбор категории'.PHP_EOL.
                        '/last - Случайные 5 аров из последнней подборки'.PHP_EOL.
                        '/help - Это справочное сообщение'.PHP_EOL.
                        '/settings - Настройки бота'.PHP_EOL,
                ));
            }

            if(strpos($val->message->text, "/settings") === 0){
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => 'Здесь будут настройки бота',
                    'reply_markup' =>  array(
                        'keyboard' => array(array('/start')),
                        'one_time_keyboard' => true,
                        'resize_keyboard'=> true
                    )
                ));
            }
            
        //}

    }

    if(property_exists($val, 'edited_message')){
        print_r($val->update_id .' '. $val->edited_message->text.'<br>');
    }

    // Remeber an id of last repalyed message
    SetCookie("tg_last_update_id", $val->update_id);    

    print('<pre>');
    print_r($response);
    print('</pre>');
}

//Check if curl installed and working
//print( "Function exist:" . function_exists('curl_version') ."<br>" );

$content = file_get_contents("php://input");    // will make php to listen for incoming request
$update = json_decode($content, false);         // start process incoming data

// Dump incoming data to file
//$req_dump = print_r( $content, true );
//$fp = file_put_contents( 'webhook_request.log', $req_dump );

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (property_exists($update, "message")) {
    webhookProcess($update);
}

// Other way to get update is supplay a bot a url so it will send all the messages to it.
// This is a webHook mechanism: If bot recieves a message, it send an HTTSP request to designited url.
// We have to process request body, and respond if needed. 'getUpdates' will not work in thi mode.
// To specify url, we have to call a 'setWebhook' once (https://core.telegram.org/bots/api#setwebhook).
// Then bot will automatically call our url when user is sends something to him.
// This method reauires a reachable addres with https protection

?>
