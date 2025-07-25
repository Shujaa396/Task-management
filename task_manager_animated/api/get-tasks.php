<?php
$tasksFile = '../data/tasks.json';
$tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

header('Content-Type: application/json');
echo json_encode($tasks);
?>
