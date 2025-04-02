<?php
// Database configuration
$host = 'localhost';
$dbname = 'password_manager';
$username = 'root';
$password = '';

// Create connection
try {
    // Set the PDO error mode to exception
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
    
    // Test the connection
    $testQuery = $conn->query('SELECT 1');
    if (!$testQuery) {
        throw new PDOException("Database connection test failed");
    }
} catch(PDOException $e) {
    // Don't expose database credentials in error messages
    throw new Exception('Database connection failed: ' . $e->getMessage());
}
?>
