<?php
require_once "../charts/lib/inc/chartphp_dist.php";
require_once "config.php";

$p = new chartphp();
$p->title = "Rozložení činností (v minutách)";
$p->chart_type = "pie";
$activitiesSums = [];
$graphActivity = [];

/* $p = new chartphp();

$heatmap_chart_data = array();
for($a=0;$a<10;$a++)
	for($b=0;$b<25;$b++)
		$heatmap_chart_data[$a][$b] = rand(0,99);

$p->data = $heatmap_chart_data;
$p->chart_type = "heatmap";
$p->heatmap_color = "violet"; // Options: green, orange, gray, hot, violet, black, blue, soft

$p->title = "HeatMap Chart";
$p->xlabel = "Department";
$p->ylabel = "Performance";

$out = $p->render('c1'); */


$totalDistance = 0;

$return = "";
$sql = "SELECT inputs.user_id, inputs.t_from, inputs.t_to, inputs.activity, users.fName, users.lName,
    users.moneyRate, activities.name, inputs.id, inputs.distance FROM inputs INNER JOIN users ON inputs.user_id=users.id
    INNER JOIN activities ON inputs.activity=activities.id
    WHERE user_id=" . $_POST["table_user"] . " AND
    MONTH(inputs.t_from)=" . intval($_POST["table_month"]) . " AND YEAR(inputs.t_from)=" . intval($_POST["table_year"]) . ";";

if ($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_row($result)) {
            $fName = $row[4];
            $lName = $row[5];
            $moneyRate = $row[6];

            $interval = date_diff(date_create($row[1]), date_create($row[2]));
            $times[] = $interval;

            $activitiesSums[$row[7]][] = $interval;

            $totalDistance += $row[9];
            
            $return .= '<tr>
            <td>
            <a href="scripts/inputs/removeInput.php?inputId=' . $row[8] . '" class="btn btn-danger pink-primary"><i class="bi bi-trash-fill"></i></a>
            <a href="scripts/inputs/editInput.php?inputId=' . $row[8] . '&&start=' . $row[1] . '&&end=' . $row[2] .'" class="btn btn-danger pink-secondary"><i class="bi bi-pencil-fill"></i></a>
            </td>
            <td>' . date_create($row[1])->format("<b>j.</b> n. Y, <b>H:i</b>") . '</td>
            <td>' . date_create($row[2])->format("<b>j.</b> n. Y, <b>H:i</b>") . '</td>
            <td>' . $row[7] . '</td>
            <td>' . $interval->format("<b>%H</b>h <b>%i</b>m") . ($row[9] > 0 ? ("\n(<b>" . $row[9] . "</b>km)") : "") . '</td>
            </tr>';
        }
    }
    else{
        $return = "Error";
    }
    mysqli_free_result($result);
}
mysqli_close($link);

if ($return != "Error"){
    foreach($activitiesSums as $key => $activity){
        for($i = 0; $i < count($activity); $i++){
            $graphActivity[0][$key] = [$key, sumMins($activity)];
        }
    }
    $p->data = $graphActivity;

    echo '<h4 class="pt-5">
    Tabulka za měsíc číslo ' . $_POST["table_month"] . '
    roku ' . $_POST["table_year"] . ' pracovníka ' . $lName . ' ' . $fName . '</h4>
    <div class="table-responsive">
    <table class="table table-hover">
    <thead>
    <tr>
    <th scope="col">Akce</th>
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
    <td scope="col"></td>
    <td scope="col">' . sumHours($times) . '</td>
    </tr>
    <tr>
    <td scope="col">Hodinová mzda</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . $moneyRate . '</b> Kč/h</td>
    </tr>
    <tr>
    <td scope="col">Vyděláno</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . getMoney($times, $moneyRate) . '</b> Kč</td>
    </tr>
    ' . ($totalDistance > 0 ? '<tr><td></td><td></td><td></td><td></td><td></td>
    </tr>
    <tr>
    <td scope="col">Celkem</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . $totalDistance . '</b>km</td>
    </tr>
    <tr>
    <td scope="col">Kilometrová mzda</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>8</b> Kč/km</td>
    </tr>
    <tr>
    <td scope="col">Vyježděno</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . $totalDistance * 8 . '</b> Kč</td>
    </tr>' : '') . '
    <tr><td></td><td></td><td></td><td></td><td></td></tr>
    <tr>
    <td scope="col">Finální suma</td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"><b>' . (($totalDistance * 8) + getMoney($times, $moneyRate)) . '</b> Kč</td>
    </tr>
    </tfoot>
    </table>
    </div>
    <div class="col-12">
    ' . $p->render('c1') . '
    </div>
    ';
}
else{
    echo "<p>Špatně zadané údaje, nebo prádné hodnoty</p>";
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

function sumMins($times){
    $minutes = 0;
    foreach ($times as $time) {
        $minutes += ($time->format('%H')) * 60;
        $minutes += ($time->format('%i'));
    }

return $minutes;
}

function getMoney($times, $rate){
    $money = 0;
    foreach ($times as $time) {
        $money += ($time->format('%H') * $rate);
        $money += ($time->format('%i') / 60) * $rate;
    }

    return $money;
}
?>