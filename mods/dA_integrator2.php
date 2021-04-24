	
<?php

require '../preinit.php';
require '../db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 0);

//error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

if (isset($_GET['token'])) {

    $i = 0;
    $ar = 0;
    $more_stat = 0;
    $json_ask = "";
    $cursor = "";
    $dups = 0;
    $lastsel = 0;
    $old_arts = 0;
    $output = array();

    $db_rq = "SELECT * FROM arts_pub WHERE addate!='" . date("j-m-Y") . "' ORDER BY aid DESC LIMIT 1";
    $artq = mysqli_query($link, $db_rq);
    if ($artq && mysqli_num_rows($artq) > 0) {
        while ($row = mysqli_fetch_array($artq, MYSQLI_ASSOC)) {
            $lastsel = $row['addate'];
        }
    }

    if ($_GET['cursor'] != "") {

        $json_ask = "https://www.deviantart.com/api/v1/oauth2/browse/deviantsyouwatch?mature_content=true&access_token=" . $_GET['token'] . "&limit=50&offset=" . $_GET['cursor'];
    } else {

        $json_ask = "https://www.deviantart.com/api/v1/oauth2/browse/deviantsyouwatch?mature_content=true&access_token=" . $_GET['token'] . "&limit=50";
    }

    // Create a stream
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']]),
        ]
    ];
    $context = stream_context_create($opts);
    $content = @file_get_contents($json_ask, false);


    $output["content"] = json_decode($content, true);

    if ($content) {

        //$feed_json = gzinflate(substr($content,10));
        $feed_json = $content;
        $feed_obj = json_decode($feed_json, true);
        $cursor = $feed_obj['next_offset'];

        $more_stat = $feed_obj['has_more'];

        //print ("Page: ".$i."<br>"); 
        $i++;


        if (isset($feed_obj['results'])) {
            foreach ($feed_obj['results'] as $deviations) {
                
                //file_put_contents('da_arts.txt', $deviations['content']['src']."|", FILE_APPEND | LOCK_EX);

                $rqchk = "SELECT * FROM arts_pub WHERE file_name='" . $deviations['content']['src'] . "'";
                $sqlchk = mysqli_query($link, $rqchk);
                if (mysqli_num_rows($sqlchk) == 0) {

                    if (strtotime($lastsel) < $deviations['published_time']) { 

                        $ar++; // add art to a counter


                        
                        $recrq = "INSERT INTO arts_pub (title, file_name, thumb, da_page, author, addate, da_id, da_tags) VALUES " .
                                "('" . $deviations['title'] . "', '" . $deviations['content']['src'] . "', '" . $deviations['thumbs'][1]['src'] . "', '" . $deviations['url'] . "', " .
                                "'" . $deviations['author']['username'] . "', '" . date("j-m-Y") . "', '" . $deviations['deviationid'] . "', '')";
                        $sqlreq = mysqli_query($link, $recrq);

                        $stylec = 'color:blue';
                        
                    } else {
                        $old_arts ++;
                        if($old_arts > 5){
                            $more_stat = false;
                        }
                    }
                } else {

                    $dups++;
                    $stylec = 'color:red';

                    /* $recrq = "UPDATE arts_pub SET (title, file_name, thumb, da_page, author, addate, da_id) VALUES ".
                      "title='".$deviations['title']."', file_name='".$deviations['content']['src']."', thumb='".$deviations['thumbs'][1]['src']."', da_page='".$deviations['url']."', ".
                      "author='".$deviations['author']['username']."', addate='".date("j-d-Y")."', da_id=".$deviations['deviationid']." ".
                      "WHERE file_name =".$deviations['content']['src']; */
                }

                $output['last_date'] = date("j-m-Y", $deviations['published_time']);

                $output['log'] .= "<tr style='" . $stylec . "'>" .
                        "<td><img src=" . $deviations['thumbs'][0]['src'] . " height='50'></td>" .
                        "<td>" . $deviations['title'] . "</td>" .
                        "<td>" . $deviations['author']['username'] . "</td>" .
                        "<td>" . date("j-m-Y", $deviations['published_time']) . "</td>" .
                        "</tr>";
            }
        }
    } else {

        $more_stat = -1;
        $cursor = $_GET['next_offset'];

        //print( json_decode( file_get_contents($json_ask) , true)['error'] );
        //print('Error 403');
    }

    // SCRIPTED in AJasKdA(token)
    $output['pages'] = $i;
    $output['arts'] = $ar;
    $output['more'] = $more_stat;
    $output['dups'] = $dups;
    $output['cursor'] = $cursor;


    print(json_encode($output));
}
?>

