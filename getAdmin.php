<?php

require('android_config.php');
require_once('config.php');
header('Content-Type: application/json');

if(!empty($_POST["adminid"])) {
    $adminid= strtoupper($_POST["adminid"]);
   
    $sql ="SELECT id, FullName, AdminEmail, AdminPhone, UserName, AdminCategory, adminStatus, updationDate 
           FROM admin WHERE id=:adminid";
    $query= $dbh->prepare($sql);
    $query->bindParam(':adminid', $adminid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC); // Fetch a single row
  
    if($result) {
        if($result['adminStatus'] == 0) {
            // If the admin is blocked, return an error message.
            echo json_encode([
                "error" => true,
                "message" => "ID Pengguna Disekat",
                "details" => null
            ]);
        } else {
            // If the admin is active, return their details.
            // Remove password from the details
            unset($result['Password']);
            echo json_encode([
                "error" => false,
                "message" => "Success",
                "details" => $result
            ]);
        }
    } else {
        // If no admin is found, return an error message.
        echo json_encode([
            "error" => true,
            "message" => "ID Pengguna Tidak Sah. Sila Masukkan ID Pengguna Yang Sah.",
            "details" => null
        ]);
    }
} else {
    echo json_encode([
        "error" => true,
        "message" => "Data POST kosong. Sila Masukkan ID Pengguna Yang Sah.",
        "details" => null
    ]);
}
?>
