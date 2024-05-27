<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

/*
if (strlen($_SESSION['alogin']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit;
}
*/

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'fetch_authors':
            $sql = "SELECT id,AuthorName, COUNT(tblbooks.ISBNNumber) AS Total from tblauthors LEFT JOIN tblbooks ON tblauthors.AuthorName = tblbooks.Author GROUP BY tblauthors.id";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'authors' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No authors found.']);
            }
            break;
    }
}
elseif (isset($_POST['add'])) {
    $categoryName = $_POST['authorName'];
    $sql = "INSERT INTO tblauthors (AuthorName) VALUES (:authorName)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':authorName', $categoryName, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Author successfully added.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add author.']);
    }
} 
// Editing an existing category
elseif (isset($_POST['edit'])) {
    // Editing an existing category
    $id = $_POST['id'];
    $categoryName = $_POST['authorName'];
    $sql = "UPDATE tblauthors SET AuthorName = :authorName WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->bindParam(':authorName', $categoryName, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Author successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update author.']);
    }
}
elseif (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM tblauthors WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Author successfully deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete author.']);
    }
}else {
    echo json_encode(['status' => 'error', 'message' => 'No valid action provided.']);
}
?>
