<?php
require_once('config.php');
require_once('android_config.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id=$_POST['id'];
    $fname = $_POST['fullanme'];
    $mobileno = $_POST['mobileno'];
    $username=$_POST['username'];
    $email = $_POST['emailid'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    $sql="UPDATE admin SET FullName=:FullName, AdminEmail=:AdminEmail, AdminPhone=:AdminPhone, UserName=:UserName, AdminCategory=:AdminCategory, adminStatus=:adminStatus WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id',$id,PDO::PARAM_STR);
    $query->bindParam(':FullName',$fname,PDO::PARAM_STR);
    $query->bindParam(':AdminEmail',$email,PDO::PARAM_STR);
    $query->bindParam(':AdminPhone',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':UserName',$username,PDO::PARAM_STR);
    // $query->bindParam(':Password',$password,PDO::PARAM_STR);
    $query->bindParam(':AdminCategory',$role,PDO::PARAM_STR);
    $query->bindParam(':adminStatus',$status,PDO::PARAM_STR);
    $result = $query->execute();

    if($result){
        $response = array('status' => 'success', 'message' => 'Admin information successfully updated.');
    }
    else{
        $response = array('status' => 'error', 'message' => 'Failed to update admin information.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Missing required parameters.');
}

echo json_encode($response);
?>
