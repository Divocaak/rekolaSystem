<?php
require_once "config.php";
session_start();

$sql = "";
for($i = 0; $i <count($_SESSION["inputText"]); $i++){
    $input = $_SESSION["inputText"][$i];
    $sql .= "INSERT INTO inputs (user_id, t_from, t_to, activity, distance) VALUES
    (" . $_SESSION["id"] . ", '" . createTimeStamp($input->day, $input->from) . "', '" . createTimeStamp($input->day, $input->to) . "', " . activityResolver($input->activity) . ", " . $input->distanceDriven . ");";
}

if (mysqli_multi_query($link, $sql)) {
    echo "Přidáno do databáze";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}

mysqli_close($link);

function createTimeStamp($day, $time){
    $time = strlen($time) < 5 ? "0" . $time : $time;

    return date("Y-m-d H:i:s", mktime(substr($time, 0, 2), substr($time, 3, 5), 0, $_SESSION["inputMonth"], $day, $_SESSION["inputYear"]));
}

function activityResolver($a){
    switch ($a) {
        case "n":
            return 1;
            break;
        case "t":
            return 2;
            break;
        case "d":
            return 3;
            break;
        case "g":
            return 4;
            break;
        case "s":
            return 5;
            break;
        case "j":
            return 6;
            break;
    }
}
?>