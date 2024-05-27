<?php

require_once('config.php');
require_once('android_config.php');

function calculateReturnDate($issueDate, $allowedBorrowingDays) {
    $returnDate = new DateTime($issueDate);
    $weekdaysCount = 0;

    while ($weekdaysCount < $allowedBorrowingDays) {
        $returnDate->modify('+1 day'); // Add one day

        // If it's not a Saturday (6) or Sunday (7), increment the weekday counter
        if ($returnDate->format('N') < 6) {
            $weekdaysCount++;
        }
    }

    // Format the return date to a readable string
    return $returnDate->format('d-m-Y h:i A'); // Adjust format as needed
}

// Assuming this script is called via a GET request with query parameters 'issueDate' and 'days'
if (isset($_GET['issueDate']) && isset($_GET['days'])) {
    $issueDate = $_GET['issueDate'];
    $days = intval($_GET['days']);
    echo calculateReturnDate($issueDate, $days);
} else {
    echo "Invalid parameters.";
}

?>
