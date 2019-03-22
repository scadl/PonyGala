<?php

require './../db_init.php';

if (isset($_GET['date'])) {
    $rq = "SELECT thumb FROM arts_pub WHERE category=" . $_GET['catn'] . " AND addate='" . $_GET['date'] . "' ORDER BY rand() LIMIT 7";
} else {
    $rq = "SELECT thumb FROM arts_pub WHERE category=" . $_GET['catn'] . " ORDER BY rand() LIMIT 7";
}

$zindex = 0;
$sql = mysqli_query($link, $rq);
while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
    print("<img src='".$row['thumb']."' height='70' style='top:".rand(0, 25)."px; left:".rand(-5, 15)."px; z-index:".$zindex."'>");
    $zindex++;
}

?>