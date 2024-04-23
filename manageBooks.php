<?php
require_once('config.php');
require_once('android_config.php');

// Assuming you have a PDO instance created and assigned to $dbh
try {
    $sql = "SELECT tblbooks.BookName, tblbooks.Category, tblbooks.Author, tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.bookImage, tblbooks.isIssued from tblbooks ORDER BY tblbooks.ISBNNumber DESC"; //WHERE (tblbooks.isIssued != 2 OR tblbooks.isIssued IS NULL;
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        // Set header to return JSON content type
        header('Content-Type: application/json');
        // Convert the result set to JSON and print it
        echo json_encode(array('status' => 'success', 'books' => $results));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No books found.'));
    }
} catch (PDOException $e) {
    // If there's a PDO exception, send a JSON error message
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
?>
