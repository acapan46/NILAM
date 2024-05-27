<?php
// Create a new endpoint for fine calculation
require_once('config.php');
require_once('android_config.php');

$daysBeforeFine = $config[2];
$renewCost = $config[4];
$damageCost = $config[5];
$endDate = date('Y-m-d'); // Today's date for comparison

if (isset($_GET['startDate'])) {
    $startDate = $_GET['startDate'];
    $numOfDates = getBusinessDatesCount($startDate, $endDate);
    $denda = getKadarDenda($numOfDates, $daysBeforeFine, $renewCost);

    echo json_encode([
        'status' => 'success',
        'message' => 'Fine calculated successfully.',
        'fine' => $denda
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No start date provided.']);
}

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
        $fine = $daysOverdue * $renewCost;
        return number_format($fine, 2, '.', ''); // Format to 2 decimal places
    }
    return number_format(0, 2, '.', ''); // Return "0.00" if no fine
}
?>