<?php
//echo $_POST["input_text"];
$input = "6/2021 .3) 10:50 12:30 s !44! | 12:30 13:45 d .5) 11:50 13:40 d | 18:00 20:55 s !36! .16) 10:00 13:30 t";
$input = preg_replace('/\s+/', '', $input);

echo $input . "<br>";
$slashPos = strpos($input, "/");
$month = substr($input, 0, $slashPos);
$year = substr($input, $slashPos + 1, 4);
echo "m: -" . $month . "-<br>";
echo "y: -" . $year . "-<br>";
$input = substr($input, strpos($input, "."));

echo "<br> mam y a m, jdu na inputy: <br>";
echo $input . "<br>";
echo "<br>";

$inputs = [];
$iMax = substr_count($input, ".");
for($i = 0; $i < $iMax; $i++){
    echo "i: " . $i . "<br>";
    $curr = substr($input, 0, (($i == ($iMax - 1)) ? strlen($input) : strpos($input, ".", 1)));
    echo "-" . $curr . "-<br>";
    
    $inputs[] = $curr;
    $input = str_replace($curr, "", $input);
    echo "remaining: " . $input . "<br><br>";
}
echo "<br>";

$realInputs = [];
for($i = 0; $i < count($inputs); $i++){
    $curr = $inputs[$i];
    
    $day = substr($curr, 1, strpos($curr, ")") - 1);
    $curr = substr($curr, strpos($curr, ")") + 1);
    echo "d: -" . $day . "-<br>";
    echo "-" . $curr . "-<br>";
    
    $num = substr_count($curr, ":") / 2;
    echo "intervals: " . $num . "<br>";
    for($j = 0; $j < $num; $j++){
        $from = substr($curr, strpos($curr, ":") - 2, 5);
        $to = substr($curr, strpos($curr, ":", 3) - 2, 5);
        $a = substr($curr, strpos($curr, ":", 3) + 3, 1);
        
        
        echo "f: -" . $from . "-<br>";
        echo "t: -" . $to . "-<br>";
        echo "a: -" . $a . "-<br>";
        if($a == "s"){
            $mFirst = strpos($curr, "!") + 1;
            $mSecond = strpos($curr, "!", $mFirst);
            $dist = substr($curr, $mFirst, $mSecond - $mFirst);
            echo "dist: -" . $dist . "-<br>";
        }
        
        $curr = str_replace(substr($curr, 0, (($a == "s") ? $mSecond + 2 : strpos($curr,$a) + 2)), "", $curr);
        echo "rem: -" . $curr . "-<br>";
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