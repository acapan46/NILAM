<?php
// Include your database configuration file
require_once('config.php');
require_once('android_config.php');

// Set header to return JSON
header('Content-Type: application/json');

// Connect to database
try {
    // Prepare and execute query
    $query_role = "SELECT id, title, active_flag FROM code_role WHERE active_flag = 'Y' AND id NOT IN (99) ORDER BY title";
    $stmt = $dbh->prepare($query_role);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Check if any states were found
    if($stmt->rowCount() > 0) {
        // Return results in JSON format
        echo json_encode($results);
    } else {
        // Return empty array if no states found
        echo json_encode([]);
    }
} catch (PDOException $e) {
    // Return error message in case of database connection failure
    echo json_encode(['error' => $e->getMessage()]);
}

?>
