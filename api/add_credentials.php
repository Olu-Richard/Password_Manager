<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start logging
$log = [];
$log[] = 'Script started';

// Get the actual origin
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$log[] = 'Origin: ' . $origin;

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . $origin);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    require_once '../config/db.php';
    $log[] = 'Database connection included';

    // Test database connection
    if (!isset($conn)) {
        throw new Exception('Database connection not established');
    }
    $log[] = 'Database connection verified';

    // Get POST data
    $rawInput = file_get_contents('php://input');
    $log[] = 'Raw input: ' . $rawInput;
    
    $data = json_decode($rawInput, true);
    $log[] = 'Decoded data: ' . print_r($data, true);

    // Validate required fields
    if (empty($data['service']) || empty($data['username']) || empty($data['password'])) {
        throw new Exception('All fields are required');
    }

    // For now, we'll use user_id 2 (the admin user)
    // In a real application, this would come from the authenticated user's session
    $userId = 2;
    
    // Insert the credentials
    $stmt = $conn->prepare("INSERT INTO passwords (user_id, service, username, password) VALUES (?, ?, ?, ?)");
    $log[] = 'Statement prepared';
    
    $stmt->execute([$userId, $data['service'], $data['username'], $data['password']]);
    $log[] = 'Query executed';
    
    echo json_encode([
        'success' => true,
        'message' => 'Credentials saved successfully',
        'debug_log' => $log
    ]);
} catch (Exception $e) {
    $log[] = 'Error occurred: ' . $e->getMessage();
    $log[] = 'Error trace: ' . $e->getTraceAsString();
    
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage(),
        'debug_log' => $log,
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} 