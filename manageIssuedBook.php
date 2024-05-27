<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');


$sql = "SELECT 
            tblstudents.StudentId, 
            tblstudents.FullName, 
            tblbooks.BookName, 
            tblbooks.ISBNNumber, 
            tblbooks.BookPrice, 
            tblissuedbookdetails.IssuesDate, 
            tblissuedbookdetails.id AS rid, 
            tblissuedbookdetails.ReturnDate,
            tblissuedbookdetails.renewCount 
        FROM 
            tblissuedbookdetails 
        JOIN 
            tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId 
        JOIN 
            tblbooks ON tblbooks.ISBNNumber = tblissuedbookdetails.BookId 
        WHERE 
            tblissuedbookdetails.RetrunStatus IS NULL
        UNION
        SELECT 
            admin.id, 
            admin.FullName, 
            tblbooks.BookName, 
            tblbooks.ISBNNumber, 
            tblbooks.BookPrice, 
            tblissuedbookdetails.IssuesDate, 
            tblissuedbookdetails.id AS rid, 
            tblissuedbookdetails.ReturnDate, 
            tblissuedbookdetails.renewCount 
        FROM 
            tblissuedbookdetails 
        JOIN 
            admin ON admin.id = tblissuedbookdetails.StudentId 
        JOIN 
            tblbooks ON tblbooks.ISBNNumber = tblissuedbookdetails.BookId 
        WHERE 
            tblissuedbookdetails.RetrunStatus IS NULL
        ORDER BY 
            rid;";

$query = $dbh->prepare($sql);
$result = $query->execute();
$data = $query->fetchAll(PDO::FETCH_OBJ);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Data retrieved successfully.', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve data.']);
}
?>
