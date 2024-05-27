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
        case 'fetch_categories':
            $sql = "SELECT id,CategoryName, COUNT(tblbooks.ISBNNumber) AS Total from tblcategory LEFT JOIN tblbooks ON tblcategory.CategoryName = tblbooks.Category GROUP BY tblcategory.id";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'categories' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No categories found.']);
            }
            break;
        case 'fetch_categories_list':
            $sql = "SELECT * from tblcategory";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'categories' => $results]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No categories found.']);
            }
            break;
    }
}
elseif (isset($_POST['add'])) {
    $categoryName = $_POST['categoryName'];
    $status=$_POST['status'];
    $sql = "INSERT INTO tblcategory (CategoryName,Status) VALUES (:categoryName,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    if ($query->execute()) {
        $lastInsertId = $dbh->lastInsertId();
        echo json_encode(['status' => 'success', 'message' => 'Category successfully added.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add category.']);
    }
} 
// Editing an existing category
elseif (isset($_POST['edit'])) {
    // Editing an existing category
    $id = $_POST['id'];
    $categoryName = $_POST['categoryName'];
    $sql = "UPDATE tblcategory SET CategoryName = :categoryName WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Category successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update category.']);
    }
}
elseif (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM tblcategory WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Category successfully deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete category.']);
    }
}else {
    echo json_encode(['status' => 'error', 'message' => 'No valid action provided.']);
}
?>
