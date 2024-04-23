<?php
require_once('config.php');
require_once('android_config.php');

// The state ID should be passed to this script
$state_id = isset($_GET['state_id']) ? $_GET['state_id'] : '';

header('Content-Type: application/json');

if(!empty($state_id)) {
    try {
        $query_town = "SELECT town_id, town_title FROM code_town WHERE state_id = :state_id AND active_flag = 'Y' ORDER BY town_title";
        $stmt = $dbh->prepare($query_town);
        $stmt->bindParam(':state_id', $state_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode(['status' => 'success', 'towns' => $results]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'State ID not provided.']);
}
?>
