<?php
require_once('config.php');
require_once('android_config.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $sid = $_POST['studentid'];
    $fname = $_POST['fullanme'];
    $mobileno = $_POST['mobileno'];
    $email = $_POST['emailid'];
    $standard = $_POST['standard'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $postcode = $_POST['postcode'];
    $state = $_POST['state'];
    $town = $_POST['town'];
    $nextofkinname = $_POST['nextofkinname'];
    $nextofkinno = $_POST['nextofkinno'];
    
    $sql="UPDATE tblstudents SET FullName = :fname, MobileNumber = :mobileno, EmailId = :email, classStandard = :classStandard, Address = :address, Address2 = :address2, PostCode = :postcode, Town = :town, State = :state, NextOfKinName = :nextofkinname, NextOfKinNo = :nextofkinno WHERE StudentId = :sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':classStandard',$standard,PDO::PARAM_STR);
    $query->bindParam(':address',$address,PDO::PARAM_STR);
    $query->bindParam(':address2',$address2,PDO::PARAM_STR);
    $query->bindParam(':postcode',$postcode,PDO::PARAM_STR);
    $query->bindParam(':state',$state,PDO::PARAM_STR);
    $query->bindParam(':town',$town,PDO::PARAM_STR);
    $query->bindParam(':nextofkinname',$nextofkinname,PDO::PARAM_STR);
    $query->bindParam(':nextofkinno',$nextofkinno,PDO::PARAM_STR);
    $query-> bindParam(':sid', $sid, PDO::PARAM_STR);
    $result = $query->execute();

    if($result) {
        $response = array('status' => 'success', 'message' => 'User information successfully updated.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update user information.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Missing required parameters.');
}

echo json_encode($response);
?>
