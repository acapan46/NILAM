<?php
require_once('config.php');
require_once('android_config.php');

$response = array();
/*
if(strlen($_SESSION['alogin'])==0) {
    // Not logged in
    $response = array('status' => 'error', 'message' => 'Session not valid. Please log in.');
    echo json_encode($response);
    exit;
}
*/

if(isset($_POST['update'])) {
    $bookname=$_POST['bookname'];
    $category=$_POST['category'];
    $author=$_POST['author'];
    $isbn=$_POST['isbn'];
    $price=$_POST['price'];
    $publisher=$_POST['publisher'];
    $pagenumber =$_POST['pagenumber'];
    $synopsis=htmlspecialchars($_POST['synopsis']);
    $bookid=$_GET['bookid'];

    $sql="update tblbooks set BookName=:bookname,Category=:category,Author=:author,BookPrice=:price,bookSynopsis=:synopsis,bookPublisher=:publisher,bookPageNumber=:pagenumber where ISBNNumber=:bookid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookname',$bookname,PDO::PARAM_STR);
    $query->bindParam(':category',$category,PDO::PARAM_STR);
    $query->bindParam(':author',$author,PDO::PARAM_STR);
    $query->bindParam(':price',$price,PDO::PARAM_STR);
    $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
    $query->bindParam(':publisher',$publisher,PDO::PARAM_STR);
    $query->bindParam(':pagenumber',$pagenumber,PDO::PARAM_STR);
    $query->bindParam(':synopsis',$synopsis,PDO::PARAM_STR);
    $result = $query->execute();

    if($result) {
        $response = array('status' => 'success', 'message' => 'Book information successfully updated.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update book information.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Missing required parameters.');
}

echo json_encode($response);
?>
