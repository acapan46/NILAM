<?php
require('config.php');
$configuration = "SELECT c.id, c.config, c.value FROM configuration c";
$query = $dbh -> prepare($configuration);
$query->execute();
$array=$query->fetchAll(PDO::FETCH_OBJ);
$config = array();
$var = 1;
foreach($array as $conf){
    $config[$var] = htmlentities($conf->value);
    $var++;
}
?>