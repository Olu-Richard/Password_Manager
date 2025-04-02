<?php
require '../config/db.php';
require '../config/jwt.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $decoded = validateJWT($token);
    
    if (!$decoded) {
        echo json_encode(["status" => "error", "message" => "Invalid session"]);
        exit;
    }

    $user_id = $decoded->user_id;
    $site_name = $_POST['site_name'];
    $site_url = $_POST['site_url'];
    $username = $_POST['username'];
    $password = openssl_encrypt($_POST['password'], 'AES-128-ECB', 'encryption_key');

    $stmt = $pdo->prepare("INSERT INTO passwords (user_id, site_name, site_url, username, password_encrypted) VALUES (:user_id, :site_name, :site_url, :username, :password)");
    if ($stmt->execute(['user_id' => $user_id, 'site_name' => $site_name, 'site_url' => $site_url, 'username' => $username, 'password' => $password])) {
        echo json_encode(["status" => "success", "message" => "Password saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error saving password"]);
    }
}
?>
