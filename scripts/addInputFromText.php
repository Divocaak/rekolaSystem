<?php
//echo $_POST["input_text"];
$input = "6/2021 .3) 10:50 12:30 t | 12:30 13:45 d .5) 11:50 13:40 d | 18:00 20:55 s !36!";

echo $input . "<br>";
$slashPos = strpos($input, "/");
$month = substr($input, 0, $slashPos);
$year = substr($input, $slashPos + 1, 4);
echo "m: -" . $month . "-<br>";
echo "y: -" . $year . "-<br>";
$input = substr($input, strpos($input, "."));

echo "<br> mam y a m, jdu na inputy: <br>";
echo $input . "<br>";


//__________________________________ODSTRANIT MEZERY, PAK AŽ ZAČÍT TAHAT DATA_______
//__________________________________ODSTRANIT MEZERY, PAK AŽ ZAČÍT TAHAT DATA_______
//__________________________________ODSTRANIT MEZERY, PAK AŽ ZAČÍT TAHAT DATA_______


$inputs = [];
for($i = 0; $i < substr_count($input, ".") + 1; $i++){
    $curr = substr($input, 0, strpos($input, ".", 1) - 1);
    echo "-" . $curr . "-<br>";
    
    $inputs[] = $curr;
    $input = substr($input, strpos($input, ".", 1));
}
echo "<br>";

$realInputs = [];
for($i = 0; $i < count($inputs); $i++){
    $curr = $inputs[$i];
    
    $day = substr($curr, 1, strpos($curr, ")") - 1);
    $curr = substr($curr, strpos($curr, ")") + 2);
    echo "-" . $curr . "-<br>";
    echo "d: -" . $day . "-<br>";
    
    for($j = 0; $j < substr_count($curr, ":") / 2; $j++){
        $from = substr($curr, strpos($curr, ":") - 2, 5);
        $curr = substr($curr, strpos($curr, ":"), 3);
        $to = substr($curr, strpos($curr, ":") - 2, 5);
        
        echo "f: -" . $from . "-<br>";
        echo "t: -" . $to . "-<br>";
    }

    echo "<br>";
}

class Input{
    public $day;
    public $from;
    public $to;
    public $activity;
    public $distanceDriven;

    function __construct($day, $from,
    $to,
    $activity, $distanceDriven) {
        $this->day = $day;
        $this->from = $from;
        $this->to = $to;
        $this->activity = $activity;
        $this->distanceDriven = $distanceDriven;
    }
}
?>