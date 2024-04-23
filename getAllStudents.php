<?php

require('android_config.php');
require_once('config.php');
header('Content-Type: application/json');

// Fetch all student details except for the password
$sql = "SELECT StudentId, FullName, EmailId, MobileNumber, classStandard, classYear, 
        Address, Address2, PostCode, Town, State, NextOfKinName, NextOfKinNo, 
        Status, RegDate, UpdationDate, ExpiredDate FROM tblstudents";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    // Initialize an array to hold the formatted results
    $studentsDetails = array();

    foreach ($results as $result) {
        // Remove password from the details (if present)
        if (isset($result['Password'])) {
            unset($result['Password']);
        }
        
        // Append student details to the studentsDetails array
        $studentsDetails[] = $result;
    }

    // Return all student details
    echo json_encode([
        "error" => false,
        "message" => "Success",
        "details" => $studentsDetails
    ]);
} else {
    // If no students are found, return an error message.
    echo json_encode([
        "error" => true,
        "message" => "No students found.",
        "details" => null
    ]);
}

?>
