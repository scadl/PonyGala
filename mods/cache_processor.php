<?php

require './../db_init.php';

function daTokenGet() {

    global $da_client, $da_secret;
    $response = array();

    // Create a stream
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']]),
        ]
    ];
    $json_ask = "https://www.deviantart.com/oauth2/token?grant_type=client_credentials&client_id=" . $da_client . "&client_secret=" . $da_secret;

    try {
        $context = stream_context_create($opts);
        $content = @file_get_contents($json_ask, false, $context);
        if ($content) {
            $json_response = json_decode($content, true);
            if (isset($json_response['error'])) {
                $response['expires'] = $content['error_description'];
                $response['token'] = false;
            } else {
                $response['expires'] = $json_response['expires_in'];
                $response['token'] = $json_response['access_token'];
            }
        } else {
            $response['expires'] = "Error in understanding API response";
            $response['token'] = false;
        }
    } catch (Exception $e) {
        $response['expires'] = "Error in sendind API request";
        $response['token'] = false;
    }

    return $response;
}

switch ($_GET['mode']) {
    case 0:

        $response = array();
        $response = daTokenGet();

        if ($response['token']) {

            $artq = mysqli_query($link, "SELECT da_id FROM arts_pub WHERE da_id LIKE '%-%-%-%-%'");
            while ($row = mysqli_fetch_array($artq, MYSQLI_ASSOC)) {
                $response['arts_id'][] = $row['da_id'];
            }
        }

        print(json_encode($response));

        break;

    case 1:

        $response = array();
        $response['states'] = array('title' => 0, 'file' => 0, 'thumb' => 0, 'page' => 0, 'author' => 0, 'delete' => 0);
        $response['message'] = "";
        $response['token'] = $_GET['token'];

        $db_rq = 'SELECT * FROM arts_pub WHERE da_id="' . $_GET['art'] . '"';
        $artq = mysqli_query($link, $db_rq);

        if ($artq) {

            if (mysqli_num_rows($artq) > 0) {

                while ($row = mysqli_fetch_array($artq, MYSQLI_ASSOC)) {
                    
                    da_import:

                    $opts = [
                        'http' => [
                            'method' => 'GET',
                            'header' => implode("\n", ['Accept-Encoding: gzip,deflate', 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']]),
                        ]
                    ];
                    $api_rq = "https://www.deviantart.com/api/v1/oauth2/deviation/" . $_GET['art'] . "?access_token=" . $response['token'];

                    try {
                        $context = stream_context_create($opts);
                        $content = @file_get_contents($api_rq, false, $context);    // https://stackoverflow.com/questions/272361/how-can-i-handle-the-warning-of-file-get-contents-function-in-php
                        if ($content !== false) {
                            $json_response = json_decode($content, true);

                            if (isset($json_response['error'])) {

                                if (daTokenGet()['token']) {
                                    $response['token'] = daTokenGet()['token'];
                                } else {
                                    return;
                                }
                                
                                
                            } else {
                                if ($row['title'] != $json_response['title']) {
                                    $updrq = 'UPDATE arts_pub SET title="' . $json_response['title'] . '" WHERE da_id="' . $_GET['art'] . '"';
                                    mysqli_query($link, $updrq);
                                    $response['states']['title'] = 1;
                                }
                                if ($row['file_name'] != $json_response['content']['src']) {
                                    $updrq = 'UPDATE arts_pub SET file_name="' . $json_response['content']['src'] . '" WHERE da_id="' . $_GET['art'] . '"';
                                    mysqli_query($link, $updrq);
                                    $response['states']['file'] = 1;
                                }
                                if ($row['thumb'] != $json_response['thumbs'][1]['src']) {
                                    $updrq = 'UPDATE arts_pub SET thumb="' . $json_response['thumbs'][1]['src'] . '" WHERE da_id="' . $_GET['art'] . '"';
                                    mysqli_query($link, $updrq);
                                    $response['states']['thumb'] = 1;
                                }
                                if ($row['da_page'] != $json_response['url']) {
                                    $updrq = 'UPDATE arts_pub SET da_page="' . $json_response['url'] . '" WHERE da_id="' . $_GET['art'] . '"';
                                    mysqli_query($link, $updrq);
                                    $response['states']['page'] = 1;
                                }
                                if ($row['author'] != $json_response['author']['username']) {
                                    $updrq = 'UPDATE arts_pub SET author="' . $json_response['author']['username'] . '" WHERE da_id="' . $_GET['art'] . '"';
                                    mysqli_query($link, $updrq);
                                    $response['states']['author'] = 1;
                                }
                            }

                            /* DB vars to dA vars.
                              $row['title'] == $json_response['title'];
                              $row['file_name'] == $json_response['content']['src'];
                              $row['thumb'] == $json_response['thumbs'][0]['src'];
                              $row['da_page'] == $json_response['url'];
                              $row['author'] == $json_response['author']['username']; */

                            $response['message'] = "dA OK!";
                        } else {

                            //print_r(error_get_last());
                            
                            // 400 - Bad request
                            // 404 - Not found
                            // 401 - Unauthorized
                            
                            if (stripos(error_get_last()['message'], '400') || stripos(error_get_last()['message'], '404')) { 
                                
                                $response['message'] = "dA OK!";
                                $updrq = 'DELETE FROM arts_pub WHERE da_id="' . $_GET['art'] . '"';
                                mysqli_query($link, $updrq);
                                $response['states']['delete'] = 1;
                                
                            } else if(stripos(error_get_last()['message'], '401')) {
                                
                                $response['message'] = "dA Refresh!";                                
                                if (daTokenGet()['token']) {
                                    $response['token'] = daTokenGet()['token'];                                    
                                } else {
                                    return;
                                }
                            } else {
                                $response['message'] = "Strange dA Error: " . error_get_last()['message'];
                            }
                            
                            error_clear_last();
                        }
                    } catch (Exception $e) {
                        $response['message'] = "Error in sendind API request";
                    }
                }
            } else {
                $response['message'] = "0 rows got; RQ:" . $artq;
            }
        } else {
            $response['message'] = mysqli_error($link);
        }

        print(json_encode($response));

        break;
}

