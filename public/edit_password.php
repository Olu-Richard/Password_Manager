<?php
session_start();
require_once '../includes/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch password data to edit
if (isset($_GET['id'])) {
    $passwordId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    // Fetch the password record from the database
    $stmt = $conn->prepare("SELECT * FROM passwords WHERE id = ? AND user_id = ?");
    $stmt->execute([$passwordId, $userId]);
    $password = $stmt->fetch();

    // Check if the password exists
    if (!$password) {
        header('Location: dashboard.php');
        exit;
    }

    // Handle form submission to update password
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $service = $_POST['service'];
        $username = $_POST['username'];
        $newPassword = $_POST['password'];

        // Update password in the database
        $updateStmt = $conn->prepare("UPDATE passwords SET service = ?, username = ?, password = ? WHERE id = ? AND user_id = ?");
        $updateStmt->execute([$service, $username, $newPassword, $passwordId, $userId]);

        // Redirect to the dashboard after updating
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}

// Function to get favicon
function getFavicon($url) {
    $url = preg_replace('/^https?:\/\//', '', $url);
    $url = preg_replace('/^www\./', '', $url);
    $url = strtok($url, '/');
    return "https://www.google.com/s2/favicons?domain=" . $url . "&sz=128";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Password - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            position: fixed;
            width: 250px;
            left: 0;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        .sidebar.collapsed {
            transform: translateX(-250px);
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 0;
        }
        .toggle-sidebar {
            position: fixed;
            left: 250px;
            top: 20px;
            background-color: #333;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: left 0.3s ease;
            z-index: 1000;
        }
        .toggle-sidebar.collapsed {
            left: 0;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover {
            background-color: #4CAF50;
            border-left: 3px solid #fff;
        }
        .edit-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        .site-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .favicon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .password-field {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 10;
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-center mb-4">Password Manager</h2>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="add_password.php"><i class="fas fa-plus me-2"></i>Add Password</a>
        <a href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="edit-card">
            <div class="site-info">
                <img src="<?php echo getFavicon($password['service']); ?>" class="favicon" alt="Site Icon">
                <h2 class="mb-0">Edit Password</h2>
            </div>

            <form method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="service" name="service" value="<?php echo htmlspecialchars($password['service']); ?>" required placeholder="Website URL">
                    <label for="service">Website URL</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($password['username']); ?>" required placeholder="Username">
                    <label for="username">Username</label>
                </div>
                <div class="form-floating password-field">
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password['password']); ?>" required placeholder="Password">
                    <label for="password">Password</label>
                    <i class="far fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
                
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleButton = document.querySelector('.toggle-sidebar');
        
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        toggleButton.classList.toggle('collapsed');
    }

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>
