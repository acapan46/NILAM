<?php
session_start();
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

/*
if(strlen($_SESSION['alogin'])==0) {   
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}
*/

if(isset($_POST['issue']) && isset($_POST['bookno'])) {
    $result_id = $_POST['issue'];
    $bookid = $_POST['bookno'];
    $renewCount = $config[3];
    $status = 1; // book issued

    try {
        $dbh->beginTransaction();

        $sql = "UPDATE tblissuedbookdetails SET IssuesDate = CURRENT_TIMESTAMP, ReturnDate = NULL, RetrunStatus = NULL, renewCount = :renewCount WHERE id = :rid;
                update tblbooks set isIssued = :status where ISBNNumber=:bookid;";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $result_id, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':renewCount', $renewCount, PDO::PARAM_STR);
        $query->execute();
        $dbh->commit();

        echo json_encode(['status' => 'success', 'message' => "Book ISBN $bookid has been successfully issued."]);
    } catch (Exception $e) {
        $dbh->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
