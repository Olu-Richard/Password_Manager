<?php
require_once 'db.php';
require_once 'C:/xampp/htdocs/Richard_Olummanuel/Project_Manager/vendor/autoload.php';


use OTPHP\TOTP;

function registerUser($username, $email, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
    
    return $conn->lastInsertId();
}

function loginUser($email, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false; 
}

function setupMFA($userId) {
    $totp = TOTP::create();
    $secret = $totp->getSecret();
    
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET mfa_secret = ? WHERE id = ?");
    $stmt->execute([$secret, $userId]);
    
    return $totp->getProvisioningUri("PasswordManagerApp");
}

function verifyMFA($userId, $code) {
    global $conn;
    $stmt = $conn->prepare("SELECT mfa_secret FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if ($user) {
        $totp = TOTP::create($user['mfa_secret']);
        return $totp->verify($code);
    }
    
    return false;
}
?>
