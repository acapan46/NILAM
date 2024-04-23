<?php
require('config.php');

$makeQuery = "SELECT * FROM tblcategory";
$statement = $conn->prepare($makeQuery);
$statement->execute();

$myArray = array();

while($resultsFrom = $statement->fetch()){
    // Corrected the array_push usage
    array_push($myArray, array(
        "id" => $resultsFrom['id'],
        "CategoryName" => $resultsFrom['CategoryName'],
        "Status" => $resultsFrom['Status'],
        "CreationDate" => $resultsFrom['CreationDate'],
        "UpdationDate" => $resultsFrom['UpdationDate']
    ));
}

// Corrected the echo statement for JSON encoding
echo json_encode($myArray);
?>
