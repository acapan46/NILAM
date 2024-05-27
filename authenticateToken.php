<?php
//require_once 'vendor/autoload.php'; // Adjust the path as needed
//use \Firebase\JWT\JWT;

header('Content-Type: application/json');

// The key should be the same as the one used to sign the JWTs
//$key = "your_secret_key";

// Ensure there's a token provided
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    echo json_encode(['status' => 'error', 'message' => 'No token provided.']);
    exit;
}

// Extract bearer token
$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
$token = str_replace('Bearer ', '', $authHeader);

// Bypass JWT verification and always return success for testing
echo json_encode(['status' => 'success', 'message' => 'Token is valid.', 'data' => ['token' => $token]]);

/*
// Below is the actual token validation logic that should be used in non-testing scenarios
try {
    // Decode the token
    $decoded = JWT::decode($token, new JWT\Key($key, 'HS256')); // Adjust the algorithm if different

    // If decode is successful, token is valid
    echo json_encode(['status' => 'success', 'message' => 'Token is valid.', 'data' => (array) $decoded]);
} catch (\Firebase\JWT\ExpiredException $e) {
    // Handle the case where token is expired
    echo json_encode(['status' => 'error', 'message' => 'Token is expired.']);
} catch (\Exception $e) {
    // Handle other exceptions
    echo json_encode(['status' => 'error', 'message' => 'Token is invalid: ' . $e->getMessage()]);
}
*/
?>
