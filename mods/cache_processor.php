<?php
require './../db_init.php';

function ParseName($thumb)
{

    $srcfnm = str_replace("_", " ", $thumb);
    $strlen = strlen($srcfnm);

    $possl = strripos($srcfnm, '/');
    $posby = stripos($srcfnm, 'by');
    $posmn = strripos($srcfnm, '-');

    $aunmf = substr($srcfnm, $posby);

    $nameln = $posby - $possl - 2;
    $autnln = $posmn - $posby - 3;

    $nanmst = substr($srcfnm, $possl + 1, $nameln);
    $autnnm = substr($srcfnm, $posby + 3, $autnln);

    if ($nanmst == "") {
        $nanmst = "DigitalArt N" . rand(0, 150);
        $autnnm = "unknown_author_" . rand(0, 1000);
    }

    $output['title'] = $nanmst;
    $output['author'] = $autnnm;
    $output['da_page'] = "http://" . str_replace(" ", "-", $autnnm) . ".deviantart.com/";

    return $output;
}

switch ($_GET['mode']) {
    case 0:

        $artq = mysqli_query($link, "SELECT aid FROM arts_pub");
        while ($row = mysqli_fetch_array($artq, MYSQLI_ASSOC)) {
            print($row['aid'] . '|');
        }

        break;
    case 1:

        $states = array(0, 0, 0, 0, 0, 0);

        $artq = mysqli_query($link, "SELECT * FROM arts_pub WHERE aid=" . $_GET['check']);
        while ($row = mysqli_fetch_array($artq, MYSQLI_ASSOC)) {

            $ch = curl_init($row['file_name']);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // $retcode >= 400 -> not found, $retcode = 200, found.            
            if ($retcode >= 400) {
                $updrq = 'DELETE FROM arts_pub WHERE aid=' . $_GET['check'];
                mysqli_query($link, $updrq);
                $states[5] = 1;
            } else {

                $parsed_params = ParseName($row['file_name']);

                if ($row['title'] == '') {
                    $updrq = 'UPDATE arts_pub SET title="' . $parsed_params['title'] . '" WHERE aid=' . $_GET['check'];
                    mysqli_query($link, $updrq);
                    $states[0] = 1;
                }
                if ($row['file_name'] == '') {
                    $updrq = 'UPDATE arts_pub SET file_name="' . $row['thumb'] . '" WHERE aid=' . $_GET['check'];
                    mysqli_query($link, $updrq);
                    $states[1] = 1;
                }
                if ($row['thumb'] == '') {
                    $updrq = 'UPDATE arts_pub SET thumb="' . $row['file_name'] . '" WHERE aid=' . $_GET['check'];
                    mysqli_query($link, $updrq);
                    $states[2] = 1;
                }
                if ($row['da_page'] == '') {
                    $updrq = 'UPDATE arts_pub SET da_page="' . $parsed_params['da_page'] . '" WHERE aid=' . $_GET['check'];
                    mysqli_query($link, $updrq);
                    $states[3] = 1;
                }
                if ($row['author'] == '') {
                    $updrq = 'UPDATE arts_pub SET author="' . $parsed_params['author'] . '" WHERE aid=' . $_GET['check'];
                    mysqli_query($link, $updrq);
                    $states[4] = 1;
                }
            }
            curl_close($ch);
        }

        print(json_encode($states));

        break;
}
