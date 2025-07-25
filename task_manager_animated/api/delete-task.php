<?php
$data = json_decode(file_get_contents("php://input"), true);
$taskId = $data['id'] ?? null;

if ($taskId !== null) {
    $tasksFile = '../data/tasks.json';
    $tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

    $tasks = array_filter($tasks, fn($task) => $task['id'] != $taskId);
    file_put_contents($tasksFile, json_encode(array_values($tasks), JSON_PRETTY_PRINT));

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
}
?>
<?php
$taskFile = '../tasks.json';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $taskId = $data['id'];

    $tasks = json_decode(file_get_contents($taskFile), true);
    $updatedTasks = array_filter($tasks, fn($task) => $task['id'] != $taskId);
    file_put_contents($taskFile, json_encode(array_values($updatedTasks), JSON_PRETTY_PRINT));

    echo json_encode(["message" => "Task deleted"]);
}
?>
