<?php
session_start();
require_once "../config.php";

$sql = 'UPDATE inputs SET t_from="' . createTimeStamp($_POST["input_from"]) . '",
t_to="' . createTimeStamp($_POST["input_to"]) . '",
activity=' . $_POST["input_type"] . '
WHERE id=' . $_POST["input_id"] . ';';

if (mysqli_query($link, $sql)) {
    echo "Záznam upraven";
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