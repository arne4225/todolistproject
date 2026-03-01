<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$todo_id = $_POST['todo_id'] ?? null;
$new_status = $_POST['status'] ?? null;

if (!$todo_id || !in_array($new_status, ['done', 'giveup'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$pdo->beginTransaction();

try {

    $stmt = $pdo->prepare("UPDATE todos SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_status, $todo_id, $user_id]);

    if ($new_status === 'done') {
        $stmtStats = $pdo->prepare("UPDATE user_stats SET todos_done = todos_done + 1 WHERE user_id = ?");
    } else {
        $stmtStats = $pdo->prepare("UPDATE user_stats SET todos_giveup = todos_giveup + 1 WHERE user_id = ?");
    }
    $stmtStats->execute([$user_id]);

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
