<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

if (isset($_POST['studentId'])) {
    $studentId = $_POST['studentId']; // Get the studentId from POST data

    $sql = "SELECT tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, 
            tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid, 
            tblissuedbookdetails.fine, tblissuedbookdetails.StudentId 
            FROM tblissuedbookdetails 
            JOIN admin ON admin.id = tblissuedbookdetails.StudentId 
            JOIN tblbooks ON tblbooks.ISBNNumber = tblissuedbookdetails.BookId 
            WHERE tblissuedbookdetails.ReturnDate IS NULL 
            AND tblissuedbookdetails.RetrunStatus = 3 
            AND admin.id = :sid 
            ORDER BY tblissuedbookdetails.id DESC";

    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $studentId, PDO::PARAM_INT);
    $result = $query->execute();
    $data = $query->fetchAll(PDO::FETCH_OBJ);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Data retrieved successfully.', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No student ID provided.']);
}
?>
