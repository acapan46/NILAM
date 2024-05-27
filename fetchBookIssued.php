<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

// Check if the ISBN number is sent via POST
if (isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];

    $sql = "SELECT tblstudents.StudentId, tblstudents.FullName, tblbooks.BookName, tblbooks.ISBNNumber, tblbooks.BookPrice,tblbooks.bookImage, tblbooks.isIssued, 
                    tblissuedbookdetails.IssuesDate, tblissuedbookdetails.id as rid, tblissuedbookdetails.ReturnDate, 
                    tblissuedbookdetails.RetrunStatus, tblissuedbookdetails.fine, tblissuedbookdetails.renewCount
            FROM tblissuedbookdetails 
            JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId 
            JOIN tblbooks ON tblbooks.ISBNNumber = tblissuedbookdetails.BookId 
            WHERE tblissuedbookdetails.BookId = :isbn AND tblissuedbookdetails.RetrunStatus IS NULL
            LIMIT 1";

    $query = $dbh->prepare($sql);
    $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
    $result = $query->execute();
    $book = $query->fetch(PDO::FETCH_ASSOC);

    if ($book) {
        // Format the date correctly
        $book['IssuesDate'] = date('Y-m-d\TH:i:s\Z', strtotime($book['IssuesDate']));
        if ($book['ReturnDate']) {
            $book['ReturnDate'] = date('Y-m-d\TH:i:s\Z', strtotime($book['ReturnDate']));
        }
        echo json_encode(['status' => 'success', 'message' => 'Book retrieved successfully.', 'data' => $book]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Book not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No ISBN provided.']);
}
?>
