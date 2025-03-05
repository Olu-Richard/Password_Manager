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
        .settings-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .settings-form .form-group {
            margin-bottom: 15px;
        }
        .settings-form .form-group label {
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
            <h2 class="mb-4">Settings</h2>

            <!-- Update Profile Information -->
            <div class="settings-form">
                <h4>Update Profile Information</h4>
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
            <div class="settings-form mt-4">
                <h4>Change Password</h4>
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
            <div class="settings-form mt-4">
                <h4>Delete Account</h4>
                <form method="POST">
                    <p>Are you sure you want to delete your account? This action is irreversible.</p>
                    <button type="submit" name="delete_account" class="btn btn-danger">Delete Account</button>
                </form>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
