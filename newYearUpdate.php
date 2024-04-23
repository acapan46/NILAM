<?php
require_once('config.php');
require_once('android_config.php');

$response = array('status' => 'error', 'message' => 'Action failed');
$currentYear = date('Y');

// Check if the operation has already been performed this year by querying the log_naik_darjah table
$logCheckQuery = "SELECT COUNT(*) as count FROM log_naik_darjah WHERE year = :currentYear";
$logCheckStmt = $dbh->prepare($logCheckQuery);
$logCheckStmt->bindParam(':currentYear', $currentYear, PDO::PARAM_STR);
$logCheckStmt->execute();
$logCheckResult = $logCheckStmt->fetch(PDO::FETCH_ASSOC);

if ($logCheckResult['count'] > 0) {
    // If the count is greater than 0, it means the operation has been performed
    $response['message'] = 'The Kenaikan Kelas process has already been performed for the current year.';
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentYear = date('Y');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    try {
        $dbh->beginTransaction();
    
        $dbh->exec("UPDATE tblstudents SET classStandard = CASE WHEN classStandard < 6 THEN classStandard + 1 ELSE classStandard END, Status = CASE WHEN classStandard = 6 THEN 0 ELSE Status END");
    
        // Log the operation
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $insertLog = $dbh->prepare("INSERT INTO log_naik_darjah (ip_address, year, user_agent, access_date) VALUES (?, ?, ?, NOW())");
        $insertLog->execute([$ipAddress, $currentYear, $userAgent]);
    
        $dbh->commit();
        $response = ['status' => 'success', 'message' => 'Kenaikan Kelas process completed successfully.'];
    } catch (Exception $e) {
        $dbh->rollback();
        $response['message'] = 'Error: ' . $e->getMessage();
    }
}

// Return response
echo json_encode($response);
?>
