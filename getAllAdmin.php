<?php

require('android_config.php');
require_once('config.php');
header('Content-Type: application/json');

// Fetch all student details except for the password
$sql = "SELECT id, FullName, AdminEmail, AdminPhone, UserName, AdminCategory, 
        adminStatus, updationDate FROM admin";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    // Initialize an array to hold the formatted results
    $adminDetails = array();

    foreach ($results as $result) {
        // Remove password from the details (if present)
        if (isset($result['Password'])) {
            unset($result['Password']);
        }
        
        // Append student details to the adminDetails array
        $adminDetails[] = $result;
    }

    // Return all student details
    echo json_encode([
        "error" => false,
        "message" => "Success",
        "details" => $adminDetails
    ]);
} else {
    // If no students are found, return an error message.
    echo json_encode([
        "error" => true,
        "message" => "No admins found.",
        "details" => null
    ]);
}

?>
