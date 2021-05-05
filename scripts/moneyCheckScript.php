<?php
require_once "config.php";


$return = "";
$sql = "SELECT inputs.user_id, inputs.t_from, inputs.t_to, inputs.activity, users.fName, users.lName,
    users.moneyRate, activities.name FROM inputs INNER JOIN users ON inputs.user_id=users.id
    INNER JOIN activities ON inputs.activity=activities.id
    WHERE MONTH(inputs.t_from)=" . intval($_POST["table_month"]) . " AND 
    YEAR(inputs.t_from)=" . intval($_POST["table_year"]) . ";";

if ($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_row($result)) {
            $fName = $row[4];
            $lName = $row[5];
            $moneyRate = $row[6];

            $interval = date_diff(date_create($row[1]), date_create($row[2]));
            $times[] = $interval;

            $return .= '<tr>
                    <td>' . $row [1]. '</td>
                    <td>' . $row[2] . '</td>
                    <td>' . $row[7] . '</td>
                    <td>' . $interval->format("<b>%H</b>h <b>%i</b>m") . '</td>
                </tr>';
        }
    }
    else{
        $return .= "<p>Error</p>";
    }
    mysqli_free_result($result);
}
mysqli_close($link);

if ($return != ""){
    echo '<div class="col-12"><h4 class="pt-5">
    Tabulka za měsíc číslo ' . $_POST["table_month"] . '
    roku ' . $_POST["table_year"] . ' pracovníka ' . $lName . ' ' . $fName . '<h4></div>
    <div class="table-responsive">
    <table class="table table-hover">
    <thead>
    <tr>
    <th scope="col">Od</th>
    <th scope="col">Do</th>
    <th scope="col">Činnost</th>
    <th scope="col">Celkem</th>
    </tr>
    </thead>
    <tbody>' . $return . '</tbody>
    <tfoot>
    <tr>
    <td scope="col">Celkem</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col">' . sumHours($times) . '</td>
    </tr>
    <tr>
    <td scope="col">Hodinová mzda</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . $moneyRate . '</b> Kč/h</td>
    </tr>
    <tr>
    <td scope="col">Vyděláno</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . getMoney($times, $moneyRate) . '</b> Kč</td>
    </tr>
    </tfoot>
    </table>
    </div>';
}
else{
    echo "<p>Error, nebo špatně zadané údaje.</p>";
}

function sumHours($times) {
    $minutes = 0;
    foreach ($times as $time) {
        $minutes += ($time->format('%H')) * 60;
        $minutes += ($time->format('%i'));
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    return sprintf('<b>%02d</b>h <b>%02d</b>m', $hours, $minutes);
}

function getMoney($times, $rate){
    //vojeb
    if($_POST["table_user"] == 1) $rate = 130;

    $money = 0;
    foreach ($times as $time) {
        $money += ($time->format('%H') * $rate);
        $money += ($time->format('%i') / 60) * $rate;
    }

    return $money;
}
?>