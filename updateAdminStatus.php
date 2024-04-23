<?php
require_once('config.php');
require_once('android_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $sid = $_POST['id'];
    $status = $_POST['status'];

    // Update only the Status field in the tblstudents table
    $sql = "UPDATE admin SET adminStatus = :status WHERE id = :sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $result = $query->execute();

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Admin status successfully updated.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update admin status.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Missing required parameters or request method is not POST.');
}

echo json_encode($response);
?>
