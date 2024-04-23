<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');


$sql = "SELECT tblstudents.StudentId, tblstudents.FullName,tblbooks.BookName,tblbooks.ISBNNumber,tblbooks.BookPrice,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.ReturnDate from  tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.ISBNNumber=tblissuedbookdetails.BookId where tblissuedbookdetails.RetrunStatus IS NULL 
UNION
SELECT admin.id, admin.FullName,tblbooks.BookName,tblbooks.ISBNNumber,tblbooks.BookPrice,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.ReturnDate from  tblissuedbookdetails join admin on admin.id=tblissuedbookdetails.StudentId join tblbooks on tblbooks.ISBNNumber=tblissuedbookdetails.BookId where tblissuedbookdetails.RetrunStatus IS NULL
ORDER BY rid;";

$query = $dbh->prepare($sql);
$result = $query->execute();
$data = $query->fetchAll(PDO::FETCH_OBJ);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Data retrieved successfully.', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve data.']);
}
?>
