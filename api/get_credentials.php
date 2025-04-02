<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error output to avoid corrupting JSON response

// Start logging
$log = [];
$log[] = 'Script started';

// Get the actual origin
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$log[] = 'Origin: ' . $origin;

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins for now
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Get POST data
    $rawInput = file_get_contents('php://input');
    $log[] = 'Raw input: ' . $rawInput;
    
    $data = json_decode($rawInput, true);
    $log[] = 'Decoded data: ' . print_r($data, true);
    
    // Verify database connection
    require_once '../config/db.php';
    $log[] = 'Database connection included';
    
    if (!isset($conn)) {
        throw new Exception('Database connection not established');
    }
    $log[] = 'Database connection verified';

    $domain = $data['domain'] ?? '';
    $log[] = 'Received domain: ' . $domain;

    if (empty($domain)) {
        throw new Exception('Domain is required');
    }

    // Extract domain name without protocol and www
    $cleanDomain = preg_replace('#^https?://(www\.)?#i', '', $domain);
    $cleanDomain = rtrim($cleanDomain, '/');
    $log[] = 'Cleaned domain: ' . $cleanDomain;

    // Create search patterns
    $searchPatterns = [
        $cleanDomain,
        'www.' . $cleanDomain,
        'http://' . $cleanDomain,
        'https://' . $cleanDomain,
        'http://www.' . $cleanDomain,
        'https://www.' . $cleanDomain
    ];

    // Build the SQL query with multiple LIKE conditions
    $sql = "SELECT id, service, username, password FROM passwords WHERE ";
    $conditions = [];
    $params = [];
    foreach ($searchPatterns as $pattern) {
        $conditions[] = "service LIKE ?";
        $params[] = "%$pattern%";
    }
    $sql .= "(" . implode(" OR ", $conditions) . ")";
    
    $log[] = 'SQL Query: ' . $sql;
    $log[] = 'Search patterns: ' . print_r($searchPatterns, true);
    
    // Query the database for credentials matching the domain
    $stmt = $conn->prepare($sql);
    $log[] = 'Statement prepared';
    
    $stmt->execute($params);
    $log[] = 'Query executed';
    
    $credentials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $log[] = 'Raw credentials: ' . print_r($credentials, true);
    $log[] = 'Number of credentials found: ' . count($credentials);
    
    // Format credentials to match expected structure
    $formattedCredentials = array_map(function($cred) {
        return [
            'id' => $cred['id'],
            'service' => $cred['service'],
            'username' => $cred['username'],
            'password' => $cred['password']
        ];
    }, $credentials);
    
    $response = [
        'success' => true,
        'credentials' => $formattedCredentials,
        'debug_log' => $log
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    $log[] = 'Error occurred: ' . $e->getMessage();
    $log[] = 'Error trace: ' . $e->getTraceAsString();
    
    http_response_code(500);
    $response = [
        'success' => false,
        'error' => 'Server error',
        'message' => $e->getMessage(),
        'debug_log' => $log,
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT);
} 