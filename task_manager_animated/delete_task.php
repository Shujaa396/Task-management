<?php
$data = json_decode(file_get_contents("php://input"), true);
$idToDelete = $data['id'] ?? null;

if ($idToDelete !== null) {
    $tasksFile = 'data/tasks.json';
    $tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

    // Filter out the task to delete
    $tasks = array_filter($tasks, fn($task) => $task['id'] != $idToDelete);

    // Save updated tasks
    file_put_contents($tasksFile, json_encode(array_values($tasks), JSON_PRETTY_PRINT));

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}
?>
