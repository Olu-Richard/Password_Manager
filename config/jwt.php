<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "your_secret_key"; // Change this to a secure key

function createJWT($user_id, $email) {
    global $secret_key;
    $payload = [
        "iss" => "password_manager",
        "aud" => "password_manager",
        "iat" => time(),
        "exp" => time() + 3600,
        "user_id" => $user_id,
        "email" => $email
    ];
    return JWT::encode($payload, $secret_key, 'HS256');
}

function validateJWT($token) {
    global $secret_key;
    try {
        return JWT::decode($token, new Key($secret_key, 'HS256'));
    } catch (Exception $e) {
        return null;
    }
}
?>
