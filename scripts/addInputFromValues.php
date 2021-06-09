<?php
session_start();
require_once "config.php";

$sql = "INSERT INTO inputs (user_id, t_from, t_to, activity, distance) 
VALUES (" . $_SESSION["id"] . ", '" . createTimeStamp($_POST["input_from"]) . "',
 '" . createTimeStamp($_POST["input_to"]) . "', " . $_POST["input_type"] . ", " . $_POST["input_distance"] . ");";

if (mysqli_query($link, $sql)) {
    echo "Záznam přidán do databáze";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}
  
mysqli_close($link);

function createTimeStamp($time){
    $d = str_replace('-', ',', $_POST["input_day"]);
    $t = str_replace(':', ',', $time);
    $date = $t . ', 0,' . $d;
    $fulldate = explode(',', $date);
    
    $h = $fulldate[0];
    $i = $fulldate[1];
    $s = $fulldate[2];
    $y = $fulldate[3];
    $m = $fulldate[4];
    $d =$fulldate[5];

    return date("Y-m-d H:i:s", mktime($h,$i,$s,$m,$d,$y));
}
?>