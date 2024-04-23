<?php 
require_once("config.php");
require_once("android_config.php");

// Define an array to hold the response
$response = array();

// Check if the GET request has the 'bookid' parameter
if(!empty($_GET["bookid"])) {
    $bookid = $_GET["bookid"];
    
    // Prepare the SQL statement
    $sql = "SELECT * FROM tblbooks WHERE ISBNNumber = :bookid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
    $query->execute();
    
    // Fetch the results
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    // If a book is found
    if($result) {
        // Include book details in the response
        $response['status'] = 'success';
        $response['message'] = 'Book details retrieved successfully.';
        $response['book'] = $result;
    } else {
        // No book found
        $response['status'] = 'error';
        $response['message'] = 'No book found with the given ISBN.';
    }
} else {
    // Missing 'bookid' GET parameter
    $response['status'] = 'error';
    $response['message'] = 'Missing required parameter: bookid.';
}

// Set header to return JSON content
header('Content-Type: application/json');
// Send JSON response
echo json_encode($response);
?>
