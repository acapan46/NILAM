<?php 
session_start();
require_once('config.php'); // Ensure this file contains the $dbh connection to PDO
require_once('android_config.php'); 
/*
// Check if the user is logged in, if not redirect to login page
if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}
*/

// Process the form when the POST request is made
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = rand(10000,99999);
    $fname = $_POST['fullanme'] ?? '';
    $mobileno = $_POST['mobileno'] ?? '';
    $username=$_POST['username'];
    $email = $_POST['emailid'] ?? '';
    $password=md5($_POST['password']);
    $role = $_POST['role'];
    $status = 1;

    $sql="INSERT INTO admin(id,FullName,AdminEmail,AdminPhone,UserName,Password,AdminCategory,adminStatus) VALUES(:id,:FullName,:AdminEmail,:AdminPhone,:UserName,:Password,:AdminCategory,:adminStatus)";

    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->bindParam(':FullName',$fname,PDO::PARAM_STR);
    $query->bindParam(':AdminEmail',$email,PDO::PARAM_STR);
    $query->bindParam(':AdminPhone',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':UserName',$username,PDO::PARAM_STR);
    $query->bindParam(':Password',$password,PDO::PARAM_STR);
    $query->bindParam(':AdminCategory',$role,PDO::PARAM_STR);
    $query->bindParam(':adminStatus',$status,PDO::PARAM_STR);

    try {
        $query->execute();

        // Success message
        $response = array(
            'status' => 'success',
            'message' => 'Admin registration successful',
            'admin_id' => $id
        );
        echo json_encode($response);
    } catch(PDOException $e) {
        // Catch and return any errors during database interaction
        echo json_encode(array("error" => $e->getMessage()));
    }
} else {
    // Not a POST request
    echo json_encode(array("error" => "Invalid request method"));
}
?>
