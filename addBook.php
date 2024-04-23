<?php
session_start();
require_once('config.php');
require_once('android_config.php');
header('Content-Type: application/json');

/*
if (strlen($_SESSION['alogin']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}
*/

// Check if the form data to add a book is submitted
if(isset($_POST['add'])) {
    $bookname = $_POST['bookname'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $price = $_POST['price'];
    $publisher = $_POST['publisher'];
    $pagenumber = $_POST['pagenumber'];
    $synopsis = htmlspecialchars($_POST['synopsis']);
    $bookimg = $_FILES["bookpic"]["name"];
    $extension = substr($bookimg, strlen($bookimg) - 4, strlen($bookimg));
    $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
    $imgnewname = md5($bookimg . time()) . $extension;

    if (!in_array($extension, $allowed_extensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid format. Only jpg / jpeg/ png /gif format allowed']);
        exit;
    }

    move_uploaded_file($_FILES["bookpic"]["tmp_name"], "admin/bookimg/" . $imgnewname);
    $sql = "INSERT INTO tblbooks (BookName, Category, Author, ISBNNumber, BookPrice, bookImage, bookSynopsis, bookPublisher, bookPageNumber) 
            VALUES (:bookname, :category, :author, :isbn, :price, :imgnewname, :synopsis, :publisher, :pagenumber)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookname', $bookname);
    $query->bindParam(':category', $category);
    $query->bindParam(':author', $author);
    $query->bindParam(':isbn', $isbn);
    $query->bindParam(':price', $price);
    $query->bindParam(':imgnewname', $imgnewname);
    $query->bindParam(':publisher', $publisher);
    $query->bindParam(':pagenumber', $pagenumber);
    $query->bindParam(':synopsis', $synopsis);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Book successfully added.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add book.']);
    }
} elseif (isset($_GET['del'])) { // Check if the book deletion is requested
    $id = $_GET['isbn'];
    $sql = "DELETE FROM tblbooks WHERE ISBNNumber=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Book successfully deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete the book.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data submitted.']);
}
?>
