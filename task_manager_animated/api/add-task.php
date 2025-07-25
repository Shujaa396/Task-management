<?php
$data = json_decode(file_get_contents("php://input"), true);
$tasksFile = '../data/tasks.json';
$tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

$data['id'] = time();
$tasks[] = $data;

file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
?>
'id' => uniqid()
