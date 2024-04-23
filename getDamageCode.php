<?php
// Include your database configuration file
require_once('config.php');
require_once('android_config.php');


header('Content-Type: application/json');

// Connect to database
try {
    // Prepare and execute query
    $query_damage = "SELECT damage_id, damage_title FROM code_damage WHERE active_flag = 'Y' ORDER BY damage_title";
    $stmt = $dbh->prepare($query_damage);
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
