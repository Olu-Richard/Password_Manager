<?php
session_start();
require_once '../includes/db_connection.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Delete password if ID is provided
if (isset($_GET['id'])) {
    $passwordId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    // Delete the password from the database
    $stmt = $conn->prepare("DELETE FROM passwords WHERE id = ? AND user_id = ?");
    $stmt->execute([$passwordId, $userId]);

    // Redirect to the dashboard after deletion
    header('Location: dashboard.php');
    exit;
} else {
    echo "No password ID provided.";
    exit;
}
?>
