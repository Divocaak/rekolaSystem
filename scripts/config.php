<?php
$testing = false;
if($testing)
{
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'rekolaSys');
}
else
{
    define('DB_SERVER', 'sql5.webzdarma.cz');
    define('DB_USERNAME', 'rekolasysxfc6896');
    define('DB_PASSWORD', 'S_JBbLBhwC993tcMtBv5');
    define('DB_NAME', 'rekolasysxfc6896');
}
 
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>