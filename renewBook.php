<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

try {
    $dbh->beginTransaction(); // Start the transaction at the beginning of the script

    if (isset($_POST['renew'])) {
        $fine = $_POST['fine'];
        $rid = $_POST['rid'];
        $issuedDate = date('Y-m-d H:i:s');
        $returnDate = NULL;

        $sql1 = "UPDATE tblissuedbookdetails SET fine=:fine, IssuesDate=:issuedDate, ReturnDate=:returnDate WHERE id=:rid";
        $query = $dbh->prepare($sql1);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->bindParam(':fine', $fine, PDO::PARAM_STR);
        $query->bindParam(':issuedDate', $issuedDate, PDO::PARAM_STR);
        $query->bindParam(':returnDate', $returnDate, PDO::PARAM_STR);
        $query->execute();
        $query->closeCursor();

        $sql2 = "UPDATE tblissuedbookdetails SET renewCount = renewCount - 1 WHERE id=:rid;";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query2->execute();
        $query2->closeCursor();

        $dbh->commit();
        echo json_encode(['status' => 'success', 'message' => 'Book renewed successfully.']);
    }
    else if (isset($_POST['return'])) {
        $fine = $_POST['fine'];
        $rid = $_POST['rid'];
        $rstatus = 1;
        $bookid = $_POST['bookid'];

        //echo "Binding parameters for query3: rid=$rid, fine=$fine, rstatus=$rstatus\n";


        $sql3 = "UPDATE tblissuedbookdetails SET fine=:fine, RetrunStatus=:rstatus WHERE id=:rid;";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query3->bindParam(':fine', $fine, PDO::PARAM_STR);
        $query3->bindParam(':rstatus', $rstatus, PDO::PARAM_STR);
        $query3->execute();
        $query3->closeCursor();

        $sql4 = "UPDATE tblbooks SET isIssued=0 WHERE ISBNNumber=:bookid";
        $query4 = $dbh->prepare($sql4);
        $query4->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query4->execute();
        $query4->closeCursor();

        $dbh->commit();
        echo json_encode(['status' => 'success', 'message' => 'Book returned successfully.']);
    }
    else if (isset($_POST['return_damage'])) {
        $fine = $_POST['fine'];
        $rid = $_POST['rid'];
        $rstatus = 2;
        $bookid = $_POST['bookid'];

        $sql5 = "update tblissuedbookdetails set fine=:fine,RetrunStatus=:rstatus where id=:rid;
        update tblbooks set isIssued=2 where ISBNNumber=:bookid"; // 2 for rosak buku
        $query5 = $dbh->prepare($sql5);
        $query5->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query5->bindParam(':fine',$fine,PDO::PARAM_STR);
        $query5->bindParam(':rstatus',$rstatus,PDO::PARAM_STR);
        $query5->bindParam(':bookid',$bookid,PDO::PARAM_STR);
        $query5->execute();
        $query5->closeCursor();

        $dbh->commit();
        echo json_encode(['status' => 'success', 'message' => 'Damaged Book recordedsuccessfully.']);
    }

} catch (PDOException $e) {
    $dbh->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
