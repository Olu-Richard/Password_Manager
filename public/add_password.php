<?php
session_start();
require_once '../includes/db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $service = $_POST['website'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userId = $_SESSION['user_id'];

    // Insert the new password into the database
    $stmt = $conn->prepare("INSERT INTO passwords (user_id, service, username, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $service, $username, $password]);

    // Redirect to the dashboard after successful insertion
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Password - Password Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }
        .sidebar {
            background-color: #333;
            color: white;
            height: 100vh;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
            margin: 10px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #4CAF50;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-3 p-3">
            <h2 class="text-center">Password Manager</h2>
            <a href="dashboard.php">Dashboard</a>
            <a href="add_password.php">Add New Password</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 p-4">
            <h2 class="mb-4">Add New Password</h2>
            
            <!-- Form to add new password -->
            <div class="form-container">
                <form action="add_password.php" method="POST">
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="text" class="form-control" id="website" name="website" required placeholder="Enter the website name (e.g., Facebook)">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Enter your username or email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn btn-success">Add Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
