<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

try {
    $dbh->beginTransaction(); // Start the transaction at the beginning of the script

    if (isset($_POST['reserve'])) {
        $user = $_POST['userid'];
        $bookid = $_POST['rid'];
        $status=3; // 3 for status reserve book

        $sql1="INSERT INTO tblissuedbookdetails(StudentID,BookId,RetrunStatus) VALUES(:studentid,:bookid,:status)";
        $query = $dbh->prepare($sql1);
        $query->bindParam(':studentid',$user,PDO::PARAM_STR);
        $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->execute();
        $query->closeCursor();

        $sql2 = "update tblbooks set isIssued = :status where ISBNNumber=:bookid;";
        $query2 = $dbh->prepare($sql2);
        $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query2->execute();
        $query2->closeCursor();

        $dbh->commit();
        echo json_encode(['status' => 'success', 'message' => 'Book reserved successfully.']);
    }

} catch (PDOException $e) {
    $dbh->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
