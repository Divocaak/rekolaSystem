<?php
require_once "config.php";


$return = "";
$sql = "SELECT inputs.user_id, inputs.t_from, inputs.t_to, inputs.activity, users.fName, users.lName,
    activities.name FROM inputs INNER JOIN users ON inputs.user_id=users.id
    INNER JOIN activities ON inputs.activity=activities.id
    WHERE user_id=" . $_POST["table_user"] . " AND
    MONTH(inputs.t_from)=" . intval($_POST["table_month"]) . " AND YEAR(inputs.t_from)=" . intval($_POST["table_year"]) . ";";

if ($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_row($result)) {
            $fName = $row[4];
            $lName = $row[5];

            $interval = date_diff(date_create($row[1]), date_create($row[2]));
            $times[] = $interval;

            $return .= '<tr>
                    <td>' . $row [1]. '</td>
                    <td>' . $row[2] . '</td>
                    <td>' . $row[6] . '</td>
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
    echo '<p>Tabulka za měsíc číslo <b>' . $_POST["table_month"] . '</b>
    roku <b>' . $_POST["table_year"] . '</b> pracovníka <b>' . $lName . ' ' . $fName . '</b></p>
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
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col"></td>
    <td scope="col">' . $totaltime->format("<b>%H</b>h <b>%i</b>m") . '</td>
    </tr>
    </tfoot>
    </table>';
}
else{
    echo "<p>Error, nebo špatně zadané údaje.</p>";
}
?>