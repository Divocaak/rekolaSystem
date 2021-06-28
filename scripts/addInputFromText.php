<?php
require_once "config.php";
session_start();
$input = $_POST["input_text"];
$inputSaved = $input;
$input = strtolower($input);
$input = preg_replace('/\s+/', '', $input);

$slashPos = strpos($input, "/");
$month = substr($input, 0, $slashPos);
$year = substr($input, $slashPos + 1, 4);
$input = substr($input, strpos($input, "."));

$inputs = [];
$iMax = substr_count($input, ".");
for($i = 0; $i < $iMax; $i++){
    $curr = substr($input, 0, (($i == ($iMax - 1)) ? strlen($input) : strpos($input, ".", 1)));
    $inputs[] = $curr;
    $input = str_replace($curr, "", $input);
}

$realInputs = [];
for($i = 0; $i < count($inputs); $i++){
    $curr = $inputs[$i];
    
    $day = substr($curr, 1, strpos($curr, ")") - 1);
    $curr = substr($curr, strpos($curr, ")") + 1);
    
    $num = substr_count($curr, ":") / 2;
    if($num < 1){
        continue;
    }
    else{
        for($j = 0; $j < $num; $j++){
            $from = substr($curr, strpos($curr, ":") - 2, 5);
            $to = substr($curr, strpos($curr, ":", 3) - 2, 5);
            $a = substr($curr, strpos($curr, ":", 3) + 3, 1);
            
            $dist = 0;
            if($a == "s"){
                $mFirst = strpos($curr, "!") + 1;
                $mSecond = strpos($curr, "!", $mFirst);
                $dist = substr($curr, $mFirst, $mSecond - $mFirst);
            }
            
            $curr = str_replace(substr($curr, 0, (($a == "s") ? $mSecond + 2 : strpos($curr,$a) + 2)), "", $curr);
            
            $realInputs[] = new Input($day, $from, $to, $a, (($dist != 0) ? $dist : 0));
            $from = $to = $a = $mFirst = $mSecond = $dist = 0;
        }
    }
}
    
$_SESSION["inputText"] = $realInputs;
$_SESSION["inputMonth"] = $month;
$_SESSION["inputYear"] = $year;

$returnTry = "";
for($i = 0; $i < count($realInputs); $i++){
    $date = $realInputs[$i]->day . '. ' . $month . '. ' . $year . ' ';

    $start = $date . $realInputs[$i]->from; 
    $end = $date . $realInputs[$i]->to;
    $act = $realInputs[$i]->activity;

    $returnTry .= '<tr>
    <td>' . dateTimeValidation($start) . '</td>
    <td>' . dateTimeValidation($end) . '</td>
    <td>' . actValidation($act) . ($realInputs[$i]->distanceDriven > 0 ? ' (' . $realInputs[$i]->distanceDriven . ')' : '') . '</td>
    </tr>';
}

function dateTimeValidation($in){
    if(strlen($in) != 16 && strlen($in) != 17){
        return '<i class="bi bi-x-square-fill text-danger"></i> ' . $in;
    }
    else{
        return '<i class="bi bi-check-square-fill text-success"></i> ' . $in;
    }
}

function actValidation($in){
    if(!ctype_alpha($in)){
        return '<i class="bi bi-x-square-fill text-danger"></i> ' . $in;
    }
    else{
        return '<i class="bi bi-check-square-fill text-success"></i> ' . $in;
    }
}
?>
<!doctype html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Rekola sys</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="row px-5">
        <div class="col-12">
            <h4 class="pt-5 text-center">Vstup</h4>
            <p class="text-center"><?php echo $inputSaved;?></p>
        </div>
        <div class="col-12">
            <h4 class="pt-5 text-center">Náhled pro kontrolu</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Od</th>
                            <th scope="col">Do</th>
                            <th scope="col">Činnost</th>
                        </tr>
                    </thead>
                    <tbody><?php echo $returnTry;?></tbody>
                </table>
            </div>
        </div>
        <div class="col-12 pt-5 text-center">
            <a class="btn btn-primary pink-danger" href="../index.php">Zrušit (zpět)</a>
            <a id="confirmBtn" class="btn btn-primary pink-primary">Zapsat záznamy</a>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#confirmBtn').click(function() {
            $.ajax({
                url: 'inputText.php',
                type: 'post',
                complete: function(response) {
                    alert(response);
                },
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>