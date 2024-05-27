<?php
session_start();
include('config.php');

header('Content-Type: application/json');

// Function to fetch the latest student ID
function getLatestStudentId($dbh) {
    $sql = "SELECT StudentId FROM tblstudents ORDER BY StudentId DESC LIMIT 1";
    $query = $dbh->prepare($sql);
    $query->execute();
    return $query->fetch(PDO::FETCH_OBJ);
}

// Function to update system configurations
function updateConfigurations($dbh) {
    $rowCount = $_POST['rowCount'];

    for($i = 1; $i <= $rowCount; $i++){
        $value = $_POST['conid_'.$i];
        $sql = "UPDATE configuration SET value = :value WHERE id = $i";
        $query = $dbh->prepare($sql);
        $query->bindParam(':value', $value, PDO::PARAM_STR);
        $query->execute();
    }

    if(isset($_POST['conid_16'])){
        // Special case for student acronyms
        $value = $_POST['conid_16'];
        $student_acronyms = $_POST['config'];
        if(strcmp($value, $student_acronyms) != 0){
            $myfile = fopen("../studentid.txt", "w") or die("Unable to open file!");
            $txt = $value.$student_acronyms;
            fwrite($myfile, $txt);
            fclose($myfile);
        }
    }
    return true;
}

// Fetch latest student ID
if(isset($_GET['action']) && $_GET['action'] == 'fetchLatestStudentId'){
    $latestStudentId = getLatestStudentId($dbh);
    if($latestStudentId){
        echo json_encode(['status' => 'success', 'latestStudentId' => $latestStudentId->StudentId]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No latest student ID found.']);
    }
}
if(isset($_POST['fetch'])){
    $sql = "SELECT * FROM configuration";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'configurations' => $results]);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'No configurations found.']);
    }
}
// Update configurations
if(isset($_POST['update'])){
    $updateStatus = updateConfigurations($dbh);
    if($updateStatus){
        echo json_encode(['status' => 'success', 'message' => 'System configurations updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update system configurations.']);
    }
}
?>
