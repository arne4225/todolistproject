<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT status, COUNT(*) as total
    FROM todos
    WHERE user_id = ?
      AND status IN ('done', 'giveup')
    GROUP BY status
");
$stmt->execute([$userId]);

$data = [
    'done' => 0,
    'giveup' => 0
];

foreach ($stmt->fetchAll() as $row) {
    $data[$row['status']] = (int)$row['total'];
}

echo json_encode($data);