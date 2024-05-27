<?php
session_start();
require_once('config.php');
require_once('android_config.php');
error_reporting(0);
/*
if (strlen($_SESSION['alogin']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}
*/

header('Content-Type: application/json');

if (isset($_POST['action'])) {
    $username = $_POST['id'];
    $password = md5($_POST['password']);
    $newpassword = md5($_POST['newpassword']);

    $sql = "SELECT Password FROM tblstudents WHERE StudentId = :username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    
    if ($query->execute()) {
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($query->rowCount() > 0) {
            if ($_POST['action'] == 'change' && $result['Password'] == $password) {
                $con = "UPDATE tblstudents SET Password = :newpassword WHERE StudentId = :username";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1->bindParam(':username', $username, PDO::PARAM_STR);
                $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
                
                if ($chngpwd1->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Kata Laluan Berjaya Ditukar.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
                }
            } else if ($_POST['action'] == 'check') {
                // For checking password only, don't return the password itself
                if ($result['Password'] == $password) {
                    echo json_encode(['status' => 'success', 'message' => 'Password match.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Kata Laluan Semasa Salah']);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No such user found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query could not be executed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No valid action provided.']);
}
?>
