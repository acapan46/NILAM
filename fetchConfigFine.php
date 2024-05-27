<?php
session_start();
require_once('config.php');
require_once('android_config.php');

/*
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}
*/

$configData = [
    "daysBeforeFine" => $config[2], // Number of days before a fine is incurred
    "finePerDay" => $config[4], // Fine rate per day
    "maxRenew" => $config[3],
    "damageFine" => $config[5],
];

echo json_encode(["status" => "success", "config" => $configData]);
?>
