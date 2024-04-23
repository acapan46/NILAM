<?php
// Create a new endpoint for fine calculation
require_once('config.php');
require_once('android_config.php');

if (isset($_GET['startDate'])) {
    $startDate = new DateTime($_GET['startDate']);
    $endDate = new DateTime(); // Today's date
    $numOfDates = getBusinessDatesCount($startDate, $endDate);
    $denda = getKadarDenda($numOfDates, $config[2], $config[4]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Fine calculated successfully.',
        'fine' => $denda
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No start date provided.']);
}

function getBusinessDatesCount($startDate, $endDate) {
    $count = 0;
    $curDate = clone $startDate;
    while ($curDate <= $endDate) {
        if ($curDate->format('N') < 6) {
            $count++;
        }
        $curDate->modify('+1 day');
    }
    return $count;
}

function getKadarDenda($numOfDates, $daysBeforeFine, $renewCost) {
    if ($numOfDates > $daysBeforeFine) {
        return ($numOfDates - $daysBeforeFine) * $renewCost;
    }
    return 0;
}
?>