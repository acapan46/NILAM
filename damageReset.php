<?php
session_start();
require_once('config.php');
require_once('android_config.php');
error_reporting(0);

$response = array();

if(isset($_POST['change'])) {
    $id = $_POST['isbn'];

    // Assuming the 'change' field is sent to determine the action
    $changeType = $_POST['change'];
    
    if($changeType == 'deactivate') {
        $reason = $_POST['reason']; // Keep the reason if deactivating

        $sql = "UPDATE tblbooks SET bookDamageReason=:reason, isIssued = 2 WHERE ISBNNumber=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':reason', $reason, PDO::PARAM_STR);
    } else if($changeType == 'activate') {
        $sql = "UPDATE tblbooks SET bookDamageReason=NULL, isIssued = 0 WHERE ISBNNumber=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        // Note: No need to bind ':reason' here since we're setting it to NULL
    } else {
        $response = array('status' => 'error', 'message' => 'Invalid change type.');
        echo json_encode($response);
        exit; // Exit the script
    }

    if($query->execute()) {
        $action = $changeType == 'deactivate' ? 'cancellation' : 'activation';
        $response = array('status' => 'success', 'message' => "Book $action successfully confirmed.");
    } else {
        $response = array('status' => 'error', 'message' => "Failed to confirm book $action.");
    }
} else {
    $response = array('status' => 'error', 'message' => 'Missing required parameters.');
}

echo json_encode($response);
?>
