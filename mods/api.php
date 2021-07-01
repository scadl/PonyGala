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

    default:
        
    break;
    }

} else {
    $out = array();
}

print(json_encode($out));

?>