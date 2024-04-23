<?php
session_start();
require_once('config.php'); // Adjust the path as needed to your configuration file

$response = array();

try {
    $sql = "SELECT * FROM `tblcategory` WHERE `Status` = 1"; // Query active categories
    $query = $dbh->prepare($sql);
    
    if($query->execute()) {
        // Fetch all the active categories
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if(!empty($categories)) {
            $response = array('status' => 'success', 'categories' => $categories);
        } else {
            // If no categories found
            $response = array('status' => 'error', 'message' => 'No categories found.');
        }
    } else {
        // If the query did not execute successfully
        $response = array('status' => 'error', 'message' => 'Failed to retrieve categories.');
    }
} catch(PDOException $e) {
    // Catch and report any errors
    $response = array('status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage());
}

// Return the response as JSON
echo json_encode($response);
?>
