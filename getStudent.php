<?php

require('android_config.php');
require_once('config.php');
header('Content-Type: application/json');

if(!empty($_POST["studentid"])) {
    $studentid= strtoupper($_POST["studentid"]);
   
    // Select all details except for the password
    $sql ="SELECT StudentId, FullName, EmailId, MobileNumber, classStandard, classYear, 
           Address, Address2, PostCode, Town, State, NextOfKinName, NextOfKinNo, 
           Status, RegDate, UpdationDate, ExpiredDate FROM tblstudents WHERE StudentId=:studentid";
    $query= $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC); // Fetch a single row
  
    if($result) {
        if($result['Status'] == 0) {
          // If the user is blocked, return an error message.
          echo json_encode([
            "error" => true,
            "message" => "ID Pengguna Disekat",
            "details" => null
          ]);
        } else {
          // Remove password from the details
          unset($result['Password']);
          // If the user is active, return their details.
          echo json_encode([
            "error" => false,
            "message" => "Success",
            "details" => $result
          ]);
        }
    } else {
      // If no student is found, return an error message.
      echo json_encode([
        "error" => true,
        "message" => "ID Pengguna Tidak Sah. Sila Masukkan ID Pengguna Yang Sah.",
        "details" => null
      ]);
    }
}

?>
