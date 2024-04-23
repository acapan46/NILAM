<?php
session_start();
require_once('config.php');
require_once('android_config.php');

$instance_id = $config[14];
$token = $config[13];


header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An unexpected error occurred.'];
/*
if (strlen($_SESSION['alogin']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
} else {
}
*/


if (isset($_POST['issue'])) {
    $studentid = strtoupper($_POST['studentid']);
    $books = json_decode($_POST['bookid'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = 'Invalid JSON provided';
        echo json_encode($response);
        exit;
    }

    $count = count($books);

    $dbh->beginTransaction();

    try {
        $query = $dbh->prepare("SELECT * FROM tblissuedbookdetails WHERE StudentID=:studentid AND RetrunStatus IS NULL");
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->execute();
        $query->closeCursor();

        if ($query->rowCount() >= $config[1]) {
            throw new Exception('Student has exceeded the limit of borrowed books.');
        }

        foreach ($books as $bookid) {
            $bookid = trim($bookid);
            if ($bookid != '') {
                $query2 = $dbh->prepare("SELECT * FROM tblbooks WHERE ISBNNumber = :bookid AND isIssued = 1");
                $query2->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query2->execute();
                $query2->closeCursor();

                if ($query2->rowCount() > 0) {
                    throw new Exception("Book $bookid is already issued.");
                }

                $query3 = $dbh->prepare("INSERT INTO tblissuedbookdetails (StudentID, BookId, renewCount) VALUES (:studentid, :bookid, :renewCount);
                                         UPDATE tblbooks SET isIssued = 1 WHERE ISBNNumber = :bookid;");
                $query3->bindParam(':studentid', $studentid, PDO::PARAM_STR);
                $query3->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query3->bindParam(':renewCount', $config[3], PDO::PARAM_INT);
                $query3->execute();
                $query3->closeCursor();
            }
        }

        $dbh->commit();
        $response['status'] = 'success';
        $response['message'] = 'All books issued successfully.';
    } catch (Exception $e) {
        $dbh->rollBack();
        $response['message'] = $e->getMessage();
    }
    echo json_encode($response);
}
?>
