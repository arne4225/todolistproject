<?php

session_start();
header('Content-Type: application/json');

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$title = $_POST['title'] ?? '';
$due_date = $_POST['due_date'] ?? '';
$due_time = $_POST['due_time'] ?? '';

if (!$title || !$due_date || !$due_time) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

$priority = $_POST['priority'] ?? 'medium';

try {
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, title, due_date, due_time, priority) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $due_date, $due_time, $priority]);

    echo json_encode([
        'id' => $pdo->lastInsertId(),
        'title' => $title,
        'due_date' => $due_date,
        'due_time' => $due_time,
        'priority' => $priority
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}