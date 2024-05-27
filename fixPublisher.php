<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

/*
if (strlen($_SESSION['alogin']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit;
}
*/

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'fetch_publishers':
            $sql = "SELECT bookPublisher, COUNT(*) AS Total from tblbooks GROUP BY bookPublisher";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'publishers' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No publishers found.']);
            }
            break;
    }
}
// Editing an existing category
elseif (isset($_POST['edit'])) {
    $publisherName = $_POST['publisherName'];
    $primary = $_POST['primary_publisher'];
    $sql="update tblbooks set bookPublisher=:publisherName where bookPublisher=:primary";
    $query = $dbh->prepare($sql);
    $query->bindParam(':publisherName',$publisherName,PDO::PARAM_STR);
    $query->bindParam(':primary',$primary,PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Publishers successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update publisher.']);
    }
}
else {
    echo json_encode(['status' => 'error', 'message' => 'No valid action provided.']);
}
?>
