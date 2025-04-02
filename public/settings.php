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
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Handle form submission for updating user info
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profile information (username and email)
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);

        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $userId]);

        // Redirect back to settings page after update
        header('Location: settings.php');
        exit;
    }

    if (isset($_POST['change_password'])) {
        // Change password
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate password change
        if ($newPassword === $confirmPassword) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Verify current password
            if (password_verify($currentPassword, $user['password'])) {
                // Update password
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedNewPassword, $userId]);

                // Redirect back to settings page after change
                header('Location: settings.php');
                exit;
            } else {
                $error = "Current password is incorrect.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    }

    if (isset($_POST['delete_account'])) {
        // Delete user account
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        // Destroy session and redirect to login page
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Password Manager</title>
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
        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 0;
        }
        .settings-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
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
        .settings-section {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .settings-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
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
        <div class="settings-card">
            <h2 class="mb-4">Settings</h2>

            <!-- Update Profile Information -->
            <div class="settings-section">
                <h4 class="mb-3">Update Profile Information</h4>
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="settings-section">
                <h4 class="mb-3">Change Password</h4>
                <form method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="settings-section">
                <h4 class="mb-3">Delete Account</h4>
                <form method="POST">
                    <p>Are you sure you want to delete your account? This action is irreversible.</p>
                    <button type="submit" name="delete_account" class="btn btn-danger">Delete Account</button>
                </form>
            </div>

            <!-- Preferences -->
            <div class="settings-section">
                <h4 class="mb-3">Preferences</h4>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="darkMode">
                    <label class="form-check-label" for="darkMode">Dark Mode</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="autoLogout">
                    <label class="form-check-label" for="autoLogout">Auto Logout (after 30 minutes)</label>
                </div>
            </div>

            <!-- Security -->
            <div class="settings-section">
                <h4 class="mb-3">Security</h4>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="twoFactor">
                    <label class="form-check-label" for="twoFactor">Two-Factor Authentication</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="biometric">
                    <label class="form-check-label" for="biometric">Biometric Authentication</label>
                </div>
            </div>
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
    </script>
</body>
</html>
