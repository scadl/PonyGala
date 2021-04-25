	
<?php

require '../preinit.php';
require '../db_init.php';
error_reporting(0);
ini_set('display_errors', 0);

function daApiRq($json){
        // Create a stream
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']]),
            ]
        ];
        $context = stream_context_create($opts);
        $content = @file_get_contents($json, false);
        return $content;
}

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

    // Detect the last selection date
    $db_rq = "SELECT * FROM arts_pub WHERE addate!='" . date("j-m-Y") . "' ORDER BY aid DESC LIMIT 1";
    $artq = mysqli_query($link, $db_rq);
    if ($artq && mysqli_num_rows($artq) > 0) {
        while ($row = mysqli_fetch_array($artq, MYSQLI_ASSOC)) {
            $lastsel = $row['addate'];
        }
    }

    // Prepare the feed request
    if ($_GET['cursor'] != "") {
        // If it page 2 or more...
        $json_ask = "https://www.deviantart.com/api/v1/oauth2/browse/deviantsyouwatch?mature_content=true&access_token=" . $_GET['token'] . "&limit=50&offset=" . $_GET['cursor'];
    } else {
        // If it's page one...
        $json_ask = "https://www.deviantart.com/api/v1/oauth2/browse/deviantsyouwatch?mature_content=true&access_token=" . $_GET['token'] . "&limit=50";
    }

    $content = daApiRq($json_ask);
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

            // Build array of art_ids
            $arts_tags = array();
            $da_ids_arr_rq = "";
            foreach ($feed_obj['results'] as $deviations) {
                $da_ids_arr_rq .= "deviationids[]=".$deviations['deviationid']."&";
            }

            // Ask DA server about additional data, including tags, coments, exif
            $json_ask_tags = "https://www.deviantart.com/api/v1/oauth2/deviation/metadata?access_token=" . $_GET['token'] . "&".$da_ids_arr_rq."ext_submission=false&ext_camera=false&ext_stats=false&ext_collection=false&with_session=false&mature_content=true";
            $meta_obj = daApiRq($json_ask_tags);
            if($meta_obj){
                $meta_data = json_decode($meta_obj, true);
                foreach($meta_data['metadata'] as $mid => $meta){
                    foreach($meta['tags'] as $tag){
                        $arts_tags[$mid] .= $tag['tag_name'].", "; // Build a string of comma separated tags for each read art.
                    }
                }
            }

            foreach ($feed_obj['results'] as $aid => $deviations) {
                
                //file_put_contents('da_arts.txt', $deviations['content']['src']."|", FILE_APPEND | LOCK_EX);

                $rqchk = "SELECT * FROM arts_pub WHERE file_name='" . $deviations['content']['src'] . "'";
                $sqlchk = mysqli_query($link, $rqchk);
                if (mysqli_num_rows($sqlchk) == 0) {

                    if (strtotime($lastsel) < $deviations['published_time']) { 

                        $ar++; // add art to a counter
                        
                        $recrq = "INSERT INTO arts_pub (title, file_name, thumb, da_page, author, addate, da_id, da_tags) VALUES " .
                                "('" . $deviations['title'] . "', '" . $deviations['content']['src'] . "', '" . $deviations['thumbs'][1]['src'] . "', '" . $deviations['url'] . "', " .
                                "'" . $deviations['author']['username'] . "', '" . date("j-m-Y") . "', '" . $deviations['deviationid'] . "', '".$arts_tags[$aid]."')";
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
            $arts_tags = [];
            $da_ids_arr_rq = [];
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
    //$output['tests'] = $meta_data;

    print(json_encode($output));
}
?>

