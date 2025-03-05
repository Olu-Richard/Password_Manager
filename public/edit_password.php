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
        echo "Password not found.";
        exit;
    }

    // Handle form submission to update password
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $website = $_POST['website'];
        $username = $_POST['username'];
        $newPassword = $_POST['password'];

        // Update password in the database
        $updateStmt = $conn->prepare("UPDATE passwords SET website = ?, username = ?, password = ? WHERE id = ? AND user_id = ?");
        $updateStmt->execute([$website, $username, $newPassword, $passwordId, $userId]);

        // Redirect to the dashboard after updating
        header('Location: dashboard.php');
        exit;
    }
} else {
    echo "No password ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Password - Password Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Password</h2>

        <!-- Edit Password Form -->
        <form method="POST">
            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="text" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($password['website']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($password['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password['password']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Password</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Cancel</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
