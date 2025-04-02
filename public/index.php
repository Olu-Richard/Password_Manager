<?php
require_once '../includes/auth.php';

// Start the session at the top of the page
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Attempt to log the user in
    $user = loginUser($email, $password);
    
    if ($user) {
        // Set session variable
        $_SESSION['user_id'] = $user['id'];
        
        // Redirect to dashboard.php after successful login
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid login credentials. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Password Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .signup-link {
            margin-top: 10px;
            font-size: 14px;
        }
        .signup-link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login to Your Account</h2>
        
        <form method="POST">
            <input type="email" name="email" id="email" required placeholder="Enter your email" />
            <input type="password" name="password" id="password" required placeholder="Enter your password" />
            <button type="submit">Login</button>
        </form>

        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>

        <div class="signup-link">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </div>

</body>
</html>
