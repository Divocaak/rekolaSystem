<?php
session_start();
require_once "config.php";

$addQuery = $_POST["input_day"] . ", " . $_SESSION["id"] . ", " . createTimeStamp($_POST["input_from"]) . ", " . createTimeStamp($_POST["input_to"]) . ", " . $_POST["input_type"] . ", " . $_POST["input_distance"];

$sqlTry = "INSERT INTO tries (query) VALUES ('" . $addQuery . "');"; 
if (mysqli_query($link, $sqlTry)) {
    $sqlTry = "succ";
} else {
    $sqlTry = "fail";
}

$sql = "INSERT INTO inputs (user_id, t_from, t_to, activity, distance) VALUES (" . $addQuery . ");";
if (mysqli_query($link, $sql)) {
    echo "Záznam přidán do databáze";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}
  
mysqli_close($link);

function createTimeStamp($time){
    $d = str_replace('-', ',', $_POST["input_day"]);
    $d = str_replace(' ', '', $d);
    $d = ((strpos("-", $d) == 1) ? ("0" . $d) : $d);
    $d = ((strpos("-", $d, 3) == 4) ? str_replace("-", "-0", $d) : $d);
    
    $t = str_replace(':', ',', $time);
    $t = ((strlen($t) < 5) ? ("0" . $t) : $t); 

    $date = $t . ', 0,' . $d;
    $fulldate = explode(',', $date);
    
    $h = $fulldate[0];
    $i = $fulldate[1];
    $s = $fulldate[2];
    $y = $fulldate[3];
    $m = $fulldate[4];
    $d = $fulldate[5];

    return date("Y-m-d H:i:s", mktime($h,$i,$s,$m,$d,$y));
}
?>