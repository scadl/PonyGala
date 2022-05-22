

<?php
require '../preinit.php';
require '../db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BOT_TOKEN', $tg_bot_secret);
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
?>

<style>
form{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#ccc; 
    padding: 5px; 
    display:inline-block
}
</style>

<form method="POST" action="<?php print(API_URL); ?>" >
    <input type="hidden" name="method" value="setWebhook">
    URL <br> 
    <input type="text" name="url"> <br>
    certificate <br>
    <input type="file" name="certificate"> <br>
    ip_address <br>
    <input type="text" name="ip_address	"> <br>
    max_connections <br>
    <input type="number" name="max_connections"> <br>
    allowed_updates <br>
    <select name="allowed_updates" multiple> 
        <option value="message">message</option>
        <option value="edited_channel_post">edited_channel_post</option>
        <option value="callback_query">callback_query</option>
    </select> <br>
    <input type="checkbox" name="drop_pending_updates"> drop_pending_updates <br>
    <input type="submit" value="set WebHook">
</form>
<form method="POST" action="<?php print(API_URL); ?>" >
    <input type="hidden" name="method" value="setWebhook">
    <input type="hidden" name="url" value="">
    <input type="submit" value="remove WebHook">
</form>

<?php
function simpleRequest($method, $params){
    // Get the messages sended to bot throught the last 24 hours
    // getUpdates is a poll-type method (https://core.telegram.org/bots/api#getupdates)
    $params["method"] = $method;
    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    // An 'Update' object (https://core.telegram.org/bots/api#update)
    // Carry all data about users activity, captured by bot
    return json_decode(curl_exec($handle), false);
}

function sendResponseWithArt($artCatID, $tgChatID){

    global $link;

    $rq = "SELECT * FROM arts_pub WHERE category=".$artCatID." ORDER BY rand() LIMIT 1";
    $sql = mysqli_query($link, $rq);
    while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
    simpleRequest(
        'sendPhoto', 
        array(
            'chat_id' => $tgChatID,
            'parse_mode' => 'MarkdownV2',
            'photo' => $row['file_name'],
            'caption' => '*'.$row['title'].'*' . ' by _'.$row['author'].'_'
        )
    );
    simpleRequest(
        'sendMessage', 
        array(
            'chat_id' => $tgChatID,
            'text' => 'Это случайный арт из выбарнной категории.  Тебе понравилось?',
            'reply_markup' => array(
                'keyboard' => array(array('Отличный арт', 'Не не пойдет')),
                'one_time_keyboard' => true,
                'resize_keyboard'=> true
            )
        )
    );
    }
}

$response = simpleRequest('getUpdates',array());

print('<pre>');
print_r('isOk?' . $response->ok.'<br>');
foreach($response->result as $key => $val){

    if(property_exists($val, 'message')){
        
        print_r($val->update_id .' '. $val->message->text.'<br>');

        // Send a response to user, only if this message is new
        if(isset($_COOKIE['tg_last_update_id']) && $val->update_id > $_COOKIE['tg_last_update_id']){

            if (strpos($val->message->text, "/start") === 0) {
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => "Привет! Какие арты тебе нравятся?",
                    'reply_markup' => array(
                            'keyboard' => array(array('Поняшки', 'Портреты'), array('Пейзажики', 'Машинки')),
                            'one_time_keyboard' => true,
                            'resize_keyboard'=> true
                        )
                ));
            }
            if(strpos($val->message->text, "Поняшки") === 0){
                sendResponseWithArt(3, $val->message->chat->id);
            }
            if(strpos($val->message->text, "Портреты") === 0){
                sendResponseWithArt(23, $val->message->chat->id);
            }
            if(strpos($val->message->text, "Пейзажики") === 0){
                sendResponseWithArt(16, $val->message->chat->id);
            }
            if(strpos($val->message->text, "Машинки") === 0){
                sendResponseWithArt(24, $val->message->chat->id);
            }

            if(strpos($val->message->text, "Отличный арт") === 0){
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => "Мы рады что тебе понравилось. Может быть хочешь еще?",
                    'reply_markup' => array(
                            'keyboard' => array(array('Да', 'Нет')),
                            'one_time_keyboard' => true,
                            'resize_keyboard'=> true
                        )
                ));
            }

            if(strpos($val->message->text, "Да") === 0){
                
            }

            if(strpos($val->message->text, "Нет") === 0){
                
            }

            if(strpos($val->message->text, "Не не пойдет") === 0){
                simpleRequest('sendMessage', array(
                    'chat_id' => $val->message->chat->id,
                    'text' => "Жаль что тебе не нравится наш выбор. Давай попробуем другую категорию:",
                    'reply_markup' => array(
                        'keyboard' => array(array('Поняшки', 'Портреты'), array('Пейзажики', 'Машинки')),
                        'one_time_keyboard' => true,
                        'resize_keyboard'=> true
                    )
                ));
            }
            
        }

    }

    if(property_exists($val, 'edited_message')){
        print_r($val->update_id .' '. $val->edited_message->text.'<br>');
    }

    // Remeber an id of last repalyed message
    SetCookie("tg_last_update_id", $val->update_id);
}
print_r($response->result);
print('</pre>');

// Other way to get update is supplay a bot a url so it will send all the messages to it.
// This is a webHook mechanism: If bot recieves a message, it send an HTTSP request to designited url.
// We have to process request body, and respond if needed. 'getUpdates' will not work in thi mode.
// To specify url, we have to call a 'setWebhook' once (https://core.telegram.org/bots/api#setwebhook).
// Then bot will automatically call our url when user is sends something to him.
// This method reauires a reachable addres with https protection

?>
