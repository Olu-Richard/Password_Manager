<?php
session_start();

// Include the database connection
require_once '../includes/db.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user data
$userId = $_SESSION['user_id'];

// Fetch passwords for the logged-in user
$stmt = $conn->prepare("SELECT * FROM passwords WHERE user_id = ?");
$stmt->execute([$userId]);
$passwords = $stmt->fetchAll();

// Function to extract the website favicon
function getFavicon($url) {
    $url = parse_url($url, PHP_URL_HOST);
    return "https://www.google.com/s2/favicons?domain=" . $url;
}

// Function to shorten website link if it's longer than 10 characters
function shortenLink($link) {
    return strlen($link) > 10 ? substr($link, 0, 10) . '...' : $link;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Password Manager</title>
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
        .password-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .password-item:hover {
            background-color: #f1f1f1;
        }
        .password-logo {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }
        .password-link {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .password-link a {
            text-decoration: none;
            font-weight: bold;
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
            <h2 class="mb-4">Your Passwords</h2>
            <a href="add_password.php" class="btn btn-success mb-3">Add New Password</a>

            <!-- Password List -->
            <div class="row">
                <?php foreach ($passwords as $password) : ?>
                    <div class="col-md-4">
                        <div class="password-item">
                            <!-- Website Link and Logo inside the password item container -->
                            <div class="password-link">
                                <img src="<?php echo getFavicon($password['website']); ?>" class="password-logo" alt="Logo">
                                <a href="<?php echo htmlspecialchars($password['website']); ?>" target="_blank">
                                    <?php echo htmlspecialchars(shortenLink($password['website'])); ?>
                                </a>
                            </div>
                            <p class="mb-1"><strong>Username:</strong> <?php echo htmlspecialchars($password['username']); ?></p>
                            <p class="mb-1"><strong>Password:</strong> <?php echo htmlspecialchars($password['password']); ?></p>
                            <a href="edit_password.php?id=<?php echo $password['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_password.php?id=<?php echo $password['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
