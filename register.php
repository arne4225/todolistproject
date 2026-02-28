<?php

session_start();
require 'db.php';

$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = $_POST['password'];

if (!$username || !$email || !$password) {
    http_response_code(400);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $email]);

if ($stmt->fetch()) {
    http_response_code(409);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO users (username, email, password_hash)
    VALUES (?, ?, ?)
");
$stmt->execute([$username, $email, $hash]);

$_SESSION['user_id'] = $pdo->lastInsertId();

echo 'OK';
