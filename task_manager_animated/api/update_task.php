<?php
$data = json_decode(file_get_contents("php://input"), true);
$taskId = $data['id'] ?? null;

$tasksFile = '../data/tasks.json';
$tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

foreach ($tasks as &$task) {
    if ($task['id'] == $taskId) {
        $task = $data;
        break;
    }
}

file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
?>
<?php
$taskFile = '../tasks.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $taskId = $data['id'];
    $newTitle = $data['title'];
    $newStatus = $data['status'];

    $tasks = json_decode(file_get_contents($taskFile), true);
    foreach ($tasks as &$task) {
        if ($task['id'] == $taskId) {
            $task['title'] = $newTitle;
            $task['status'] = $newStatus;
            break;
        }
    }
    file_put_contents($taskFile, json_encode($tasks, JSON_PRETTY_PRINT));
    echo json_encode(["message" => "Task updated"]);
}
?>
