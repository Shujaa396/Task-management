<?php
// Get the task data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Load existing tasks
$tasksFile = 'data/tasks.json';
$tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

// Check if it's an update
if (isset($data['id'])) {
    foreach ($tasks as &$task) {
        if ($task['id'] == $data['id']) {
            $task = $data;
            break;
        }
    }
} else {
    // New task
    $data['id'] = time(); // unique ID using timestamp
    $tasks[] = $data;
}

// Save back to file
file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));

// Respond
echo json_encode(['success' => true, 'tasks' => $tasks]);
?>
