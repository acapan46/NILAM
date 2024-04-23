<?php

define('DB_HOST','localhost'); //localhost
define('DB_USER','root'); //madanico_vos
define('DB_PASS',''); //2136]R?wIq23
define('DB_NAME','epinbook'); //madanico_lib2u

try{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
//echo "Connected to database\n";
}
catch (PDOException $e)
{
exit("Error connection: " . $e->getMessage());
}
?>