<?php
require_once('config.php');
require_once('android_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['studentid']) && isset($_POST['status'])) {
    $sid = $_POST['studentid'];
    $status = $_POST['status'];

    // Update only the Status field in the tblstudents table
    $sql = "UPDATE tblstudents SET Status = :status WHERE StudentId = :sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $result = $query->execute();

    if ($result) {
        $response = array('status' => 'success', 'message' => 'User status successfully updated.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update user status.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Missing required parameters or request method is not POST.');
}

echo json_encode($response);
?>
