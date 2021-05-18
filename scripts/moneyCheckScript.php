<?php
require_once "../charts/lib/inc/chartphp_dist.php";
require_once "config.php";


$p = new chartphp();
$p->title = "Nejdražší člověk";
$p->chart_type = "pie";

$employees = [];

$graphUser = [];

$return = "";
$sql = "SELECT inputs.user_id, inputs.t_from, inputs.t_to, inputs.activity, users.fName, users.lName,
    users.moneyRate, activities.name FROM inputs INNER JOIN users ON inputs.user_id=users.id
    INNER JOIN activities ON inputs.activity=activities.id
    WHERE MONTH(inputs.t_from)=" . intval($_POST["table_month"]) . " AND 
    YEAR(inputs.t_from)=" . intval($_POST["table_year"]) . ";";

if ($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_row($result)) {
            if(!isset($employees[$row[0]])){
                $employees[$row[0]] = new Employee($row[4], $row[5], []);
            }
            
            $employees[$row[0]]->moneyMade[] = getMoney(date_diff(date_create($row[1]), date_create($row[2])), $row[6], $row[0]);
        }

        foreach($employees as $employee){
            $employeeMoney = array_sum($employee->moneyMade);
            
            $graphUser[0][$employee->fName] = [($employee->fName . " " . $employee->lName), $employeeMoney];
            
            $return .= '<tr>
            <td>' . $employee->fName . '</td>
            <td>' . $employee->lName . '</td>
            <td>' . $employeeMoney . ' Kč</td>
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
    $moneyLost = sumAllMoney($employees);
    $moneyRemaining = ($_POST["table_money"] - $moneyLost);
    
    $graphUser[0]["moneyRemaining"] = ["Zbytek", $moneyRemaining];
    $p->data = $graphUser;
    
    echo '<div class="table-responsive">
    <table class="table table-hover">
    <thead>
    <tr>
    <th scope="col">Jméno</th>
    <th scope="col">Příjmení</th>
    <th scope="col">Vyděláno</th>
    </tr>
    </thead>
    <tbody>' . $return . '</tbody>
    <tfoot>
    <tr>
    <td scope="col">Celkem</td>
    <td scope="col"></td>
    <td scope="col"><b>' . $moneyLost . '</b> Kč</td>
    </tr>
    <tr>
    <td scope="col">Zbývá</td>
    <td scope="col"></td>
    <td scope="col"><b>' . $moneyRemaining . '</b> Kč</td>
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
    echo "<p>Error, nebo špatně zadané údaje.</p>";
}

class Employee{
    public $fName;
    public $lName;
    public $moneyMade;

    function __construct($fName,
    $lName,
    $moneyMade) {
        $this->fName = $fName;
        $this->lName = $lName;
        $this->moneyMade = $moneyMade;
    }
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

function getMoney($time, $rate, $user){
    return ($time->format('%H') * $rate) + ($time->format('%i') / 60) * $rate;
}

function sumAllMoney($employees){
    $sum = 0;
    foreach($employees as $employee){
        $sum += array_sum($employee->moneyMade);
    }
    return $sum;
}
?>