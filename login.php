<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL); 
require('config.php');
require('android_config.php');
$response = array();

if(isset($_POST['emailid']) && isset($_POST['password'])) {
    $email = $_POST['emailid'];
    $password = md5($_POST['password']);
    $userGroup = 0; // Default to non-student/admin

    // Determine if the user is a student or admin based on email
    if (str_contains($email, $config[16])) {
        $userGroup = 1; // User is a student
        $sql ="SELECT * FROM tblstudents WHERE StudentId=:email and Password=:password";
    } else {
        $sql ="SELECT * FROM admin WHERE UserName=:email and Password=:password";
    }

    try {
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['message'] = 'Query execution failed: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            if ($result->Status == 1 || $result->adminStatus == 1) {
                
                $sessionToken = bin2hex(random_bytes(32));
    
                
                $response['sessionToken'] = $sessionToken;
                $response['status'] = 'success';
                $response['message'] = 'Login successful.';
                $response['user'] = array(
                    'id' => $userGroup == 1 ? $result->StudentId : $result->id,
                    'name' => $result->FullName,
                    'userGroup' => $userGroup == 1 ? 'student' : 'admin',
                );
                break;
            } else {
                // User is blocked or inactive
                $response['status'] = 'error';
                $response['message'] = 'Akaun Tidak Aktif!/nSila berhubung dengan Pusat Sumber';
                break;
            }
        }
    } else {
        // No user found
        $response['status'] = 'error';
        $response['message'] = 'ID Atau Kata Laluan Salah';
    }
} else {
    // Missing required POST data
    $response['status'] = 'error';
    $response['message'] = 'Tiada ruangan diisi';
}

echo json_encode($response);
?>
