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
    // Clean the URL to get the domain
    $url = preg_replace('/^https?:\/\//', '', $url);
    $url = preg_replace('/^www\./', '', $url);
    $url = strtok($url, '/');
    return "https://www.google.com/s2/favicons?domain=" . $url . "&sz=128";
}

// Function to shorten website link
function shortenLink($link) {
    // Remove http(s):// and www.
    $link = preg_replace('/^https?:\/\//', '', $link);
    $link = preg_replace('/^www\./', '', $link);
    // Get domain only
    $link = strtok($link, '/');
    return $link;
}

// Function to mask password
function maskPassword($password) {
    if (strlen($password) <= 2) {
        return str_repeat('*', strlen($password));
    }
    return substr($password, 0, 2) . str_repeat('*', strlen($password) - 2);
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
        .password-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .password-card:hover {
            transform: translateY(-5px);
        }
        .password-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .favicon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            object-fit: contain;
        }
        .domain-name {
            font-weight: 600;
            color: #333;
            margin: 0;
            flex-grow: 1;
        }
        .password-body {
            padding: 15px;
        }
        .credential-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        .credential-label {
            width: 100px;
            color: #666;
        }
        .credential-value {
            flex-grow: 1;
            font-family: monospace;
        }
        .action-buttons {
            padding: 15px;
            background-color: #f8f9fa;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .btn-copy {
            padding: 4px 8px;
            font-size: 0.8rem;
            margin-left: 10px;
            background-color: #e9ecef;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-copy:hover {
            background-color: #dee2e6;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Your Passwords</h2>
            <a href="add_password.php" class="btn btn-success"><i class="fas fa-plus me-2"></i>Add New Password</a>
        </div>

        <div class="row">
            <?php foreach ($passwords as $password) : ?>
                <div class="col-md-6 col-lg-4">
                    <div class="password-card">
                        <div class="password-header">
                            <img src="<?php echo getFavicon($password['service']); ?>" class="favicon" alt="Site Icon">
                            <h5 class="domain-name"><?php echo htmlspecialchars(shortenLink($password['service'])); ?></h5>
                        </div>
                        <div class="password-body">
                            <div class="credential-row">
                                <span class="credential-label">Username</span>
                                <span class="credential-value">
                                    <?php echo htmlspecialchars($password['username']); ?>
                                    <button class="btn-copy" onclick="copyToClipboard(this, '<?php echo htmlspecialchars($password['username']); ?>')" title="Copy username">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="credential-row">
                                <span class="credential-label">Password</span>
                                <span class="credential-value">
                                    <?php echo htmlspecialchars(maskPassword($password['password'])); ?>
                                    <button class="btn-copy" onclick="copyToClipboard(this, '<?php echo htmlspecialchars($password['password']); ?>')" title="Copy password">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <a href="edit_password.php?id=<?php echo $password['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="delete_password.php?id=<?php echo $password['id']; ?>" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
    function copyToClipboard(button, text) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = button.querySelector('i');
            icon.classList.remove('far', 'fa-copy');
            icon.classList.add('fas', 'fa-check');
            setTimeout(() => {
                icon.classList.remove('fas', 'fa-check');
                icon.classList.add('far', 'fa-copy');
            }, 1500);
        });
    }
    </script>
</body>
</html>
