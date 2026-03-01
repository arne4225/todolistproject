<?php

declare(strict_types=1);

session_start();
require 'db.php';

header('Content-Type: application/json');

// Login check
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$todo_id = isset($_POST['todo_id']) ? (int)$_POST['todo_id'] : 0;
$status  = $_POST['status'] ?? '';

if ($todo_id <= 0 || !in_array($status, ['done', 'giveup'], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Update todo
    $stmt = $pdo->prepare(
        "UPDATE todos 
         SET status = :status 
         WHERE id = :id AND user_id = :user_id"
    );
    $stmt->execute([
        ':status' => $status,
        ':id' => $todo_id,
        ':user_id' => $user_id
    ]);

    // Update stats
    if ($status === 'done') {
        $stmtStats = $pdo->prepare(
            "INSERT INTO user_stats (user_id, todos_done)
             VALUES (:uid, 1)
             ON DUPLICATE KEY UPDATE todos_done = todos_done + 1"
        );
    } else {
        $stmtStats = $pdo->prepare(
            "INSERT INTO user_stats (user_id, todos_giveup)
             VALUES (:uid, 1)
             ON DUPLICATE KEY UPDATE todos_giveup = todos_giveup + 1"
        );
    }

    $stmtStats->execute([':uid' => $user_id]);

    $pdo->commit();

    echo json_encode(['success' => true]);
    exit;
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
    exit;
}
