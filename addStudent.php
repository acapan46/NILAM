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
    $count_my_page = ("studentid.txt");
    $hits = file($count_my_page);
    $hits[0] ++;
    $fp = fopen($count_my_page , "w");
    fputs($fp , "$hits[0]");
    fclose($fp); 
    $StudentId= $hits[0]; 
    $fname = $_POST['fullanme'] ?? '';
    $mobileno = $_POST['mobileno'] ?? '';
    $email = $_POST['emailid'] ?? '';
    $password=md5($config[17]);     
    $standard = $_POST['standard'] ?? '';
    $year = date('Y'); // Current year
    $address = $_POST['address'] ?? '';
    $address2 = $_POST['address2'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $town = $_POST['town'] ?? '';
    $state = $_POST['state'] ?? '';
    $nextofkinname = $_POST['nextofkinname'] ?? '';
    $nextofkinno = $_POST['nextofkinno'] ?? '';

    $expiredYear = date("Y") + (6 - $standard);
    $date = strtotime("".$expiredYear."/12/31 23:59:59");
    $expiredDate = date("Y-m-d h:i:s", $date);
    $status=1;

    $sql="INSERT INTO tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,classStandard,classYear,Address,Address2,PostCode,Town,State,NextOfKinName,NextOfkinNo,Status,ExpiredDate) VALUES(:StudentId,:fname,:mobileno,:email,:password,:classStandard,:classYear,:address,:address2,:postcode,:town,:state,:nextofkinname,:nextofkinno,:status,:ExpiredDate)";

    $query = $dbh->prepare($sql);
    $query->bindParam(':StudentId',$StudentId,PDO::PARAM_STR);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->bindParam(':classStandard',$standard,PDO::PARAM_STR);
    $query->bindParam(':classYear',$year,PDO::PARAM_STR);
    $query->bindParam(':address',$address,PDO::PARAM_STR);
    $query->bindParam(':address2',$address2,PDO::PARAM_STR);
    $query->bindParam(':postcode',$postcode,PDO::PARAM_STR);
    $query->bindParam(':state',$state,PDO::PARAM_STR);
    $query->bindParam(':town',$town,PDO::PARAM_STR);
    $query->bindParam(':nextofkinname',$nextofkinname,PDO::PARAM_STR);
    $query->bindParam(':nextofkinno',$nextofkinno,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->bindParam(':ExpiredDate',$expiredDate,PDO::PARAM_STR);

    try {
        $query->execute();

        // Success message
        $response = array(
            'status' => 'success',
            'message' => 'Student registration successful',
            'student_id' => $StudentId
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
