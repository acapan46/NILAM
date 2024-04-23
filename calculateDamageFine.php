<?php
session_start();
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

// Assuming $config[2], $config[4], and $config[5] are already set up in your included config files
$daysBeforeFine = $config[2];
$renewCost = $config[4];
$damageCost = $config[5];
$bookPrice = $_POST['bookPrice'] ?? 0; // Default to 0 if not set
$startDate = $_POST['startDate']; // Expected to be passed in 'Y-m-d' format
$endDate = date('Y-m-d'); // Today's date for comparison

function getBusinessDatesCount($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end = $end->modify('+1 day'); // Include end date in the interval
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($start, $interval, $end);

    $count = 0;
    foreach ($dateRange as $date) {
        if (!in_array($date->format('N'), [6, 7])) { // 6 and 7 are Saturday and Sunday
            $count++;
        }
    }
    return $count;
}

function getKadarDenda($numOfDates, $daysBeforeFine, $renewCost) {
    if ($numOfDates > $daysBeforeFine) {
        $daysOverdue = $numOfDates - $daysBeforeFine;
        return $daysOverdue * $renewCost;
    }
    return 0;
}

function getKadarBukuHilang($numOfDates, $bookPrice, $damageCost, $daysBeforeFine, $renewCost) {
    $denda = getKadarDenda($numOfDates, $daysBeforeFine, $renewCost);
    return $denda + $damageCost + $bookPrice;
}

$numOfDates = getBusinessDatesCount($startDate, $endDate);
$totalPenalty = getKadarBukuHilang($numOfDates, $bookPrice, $damageCost, $daysBeforeFine, $renewCost);

$response = [
    'status' => 'success',
    'message' => 'Calculation completed successfully.',
    'totalPenalty' => $totalPenalty
];

echo json_encode($response);
?>
