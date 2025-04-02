<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test database connection
require_once 'config/db.php';
echo "Database connection test:\n";
var_dump($conn->query("SELECT 1")->fetch());

// Test API endpoint
$ch = curl_init('http://localhost/Richard_Olummanuel/Project_Manager/api/get_credentials.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['domain' => 'irishnews.com']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

echo "\nAPI test response:\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

curl_close($ch); 