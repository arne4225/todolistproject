<?php
session_start();
require 'db.php';

$stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
$stmt->execute([$_POST['username']]);
$user = $stmt->fetch();

if ($user && password_verify($_POST['password'], $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    echo 'OK';
} else {
    http_response_code(401);
    echo 'INVALID';
}