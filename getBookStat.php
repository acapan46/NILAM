<?php
session_start();
error_reporting(0);
require_once('config.php');
require_once('android_config.php');

header('Content-Type: application/json');

// Replace this with your authentication check
/*
if (strlen($_SESSION['alogin']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit;
}
*/

if (isset($_GET['userId']) && isset($_GET['isAdmin'])) {
    $userId = $_GET['userId'];
    $isAdmin = filter_var($_GET['isAdmin'], FILTER_VALIDATE_BOOLEAN);

    // Admins get all records, regular users only their own
    $userCondition = $isAdmin ? "" : "AND StudentID = :userId";

    // Fetch the total number of books borrowed with return status 1 or 2
    $sqlTotalBorrowed = "SELECT COUNT(*) as TotalBorrowed FROM `tblissuedbookdetails` WHERE (RetrunStatus = 1 OR RetrunStatus = 2) $userCondition";
    $queryTotalBorrowed = $dbh->prepare($sqlTotalBorrowed);
    if (!$isAdmin) {
        $queryTotalBorrowed->bindParam(':userId', $userId, PDO::PARAM_STR);
    }
    $queryTotalBorrowed->execute();
    $totalBorrowed = $queryTotalBorrowed->fetchColumn();

    // Fetch the current number of books borrowed with return status NULL and IssueDate set
    $sqlCurrentBorrowed = "SELECT COUNT(*) as CurrentBorrowed FROM `tblissuedbookdetails` WHERE RetrunStatus IS NULL AND IssuesDate IS NOT NULL $userCondition";
    $queryCurrentBorrowed = $dbh->prepare($sqlCurrentBorrowed);
    if (!$isAdmin) {
        $queryCurrentBorrowed->bindParam(':userId', $userId, PDO::PARAM_STR);
    }
    $queryCurrentBorrowed->execute();
    $currentBorrowed = $queryCurrentBorrowed->fetchColumn();

    // Return the results
    echo json_encode([
        'status' => 'success',
        'totalBooksBorrowed' => $totalBorrowed,
        'currentBooksBorrowed' => $currentBorrowed,
    ]);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Required parameters not provided.']);
    exit;
}

?>
