<?php
require '../preinit.php';
require '../db_init.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['act'])){
    
    $out = array();
    
    switch($_GET['act']){
    case 1:
        $sql = mysqli_query($link, "SELECT * FROM categories ORDER BY cat_name");
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
            if (isset($_GET['date'])) {
                $reqi = mysqli_query($link, "SELECT aid FROM arts_pub WHERE category=" . $row['cat_id'] . " AND addate='" . $_GET['date'] . "'");
                $reqt = mysqli_query($link, "SELECT thumb FROM arts_pub WHERE category=" . $row['cat_id'] . " AND addate='" . $_GET['date'] . "' ORDER BY rand() LIMIT 5");
            } else {
                $reqi = mysqli_query($link, "SELECT aid FROM arts_pub WHERE category=" . $row['cat_id']);
                $reqt = mysqli_query($link, "SELECT thumb FROM arts_pub WHERE category=" . $row['cat_id'] . " ORDER BY rand() LIMIT 5");
            }

            $index = 1;
            while ($row_tb = mysqli_fetch_array($reqt, MYSQLI_ASSOC)){
                $row['thumb_'.$index] = $row_tb['thumb'];
                $index++;
            }

            
                for($i = $index; $i <= 5; $i++ ){
                    $row['thumb_'.$i] = "https://artgala.scadsdnd.net/img/no-arts.png";
                }
            

            $row['count'] = mysqli_num_rows($reqi);

            $out[] = $row;
        }
        break;

    case 2:
        if (isset($_GET['date'])){
            $sql = mysqli_query($link, "SELECT * FROM arts_pub WHERE category=" . $_GET['cat_id'] . " AND addate='" . $_GET['date'] . "'");
        } else {
            $sql = mysqli_query($link, "SELECT * FROM arts_pub WHERE category=" . $_GET['cat_id']);
        }
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
            $out[] = $row;
        }
        break;
    
        case 3:
            $row = array();

            if (isset($_GET['date'])) {
                $reqt = mysqli_query($link, "SELECT thumb FROM arts_pub WHERE category=" . $row['cat_id'] . " AND addate='" . $_GET['date'] . "' ORDER BY rand() LIMIT 5");
            } else {
                $reqt = mysqli_query($link, "SELECT thumb FROM arts_pub WHERE category=" . $row['cat_id'] . " ORDER BY rand() LIMIT 5");
            }

            $index = 1;
            while ($row_tb = mysqli_fetch_array($reqt, MYSQLI_ASSOC)){
                $row['thumb_'.$index] = $row_tb['thumb'];
                $index++;
            }

            $out[] = $row;
            break;

        case 4:
            
            $publication = array();
            $sql = mysqli_query($link, "SELECT file_name, addate FROM arts_pub GROUP BY addate");
                        
            while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
                $publication[] = $row['addate'];                
            } 
                
            $out[] = $publication;
            break;

    default:
        
    break;
    }

} else {
    $out = array();
}

print(json_encode($out));

?>