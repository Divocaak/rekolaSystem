<?php
$testing = true;
if($testing)
{
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'rekolaSys');
}
else
{
    define('DB_SERVER', 'sql4.webzdarma.cz');
    define('DB_USERNAME', 'cukrovkaxfcz1566');
    define('DB_PASSWORD', 'Pgema333@');
    define('DB_NAME', 'cukrovkaxfcz1566');
}
 
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>