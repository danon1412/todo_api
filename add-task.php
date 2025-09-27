<?php
// Скрипт для добавления тестовых задач
function addTask($data) {
    $url = 'http://localhost/todo-api/tasks';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

// Добавляем несколько тестовых задач
$testTasks = [
    [
        'title' => 'Изучить PHP',
        'description' => 'Освоить основы программирования на PHP',
        'status' => 'completed'
    ],
    [
        'title' => 'Создать REST API',
        'description' => 'Разработать простое API для управления задачами',
        'status' => 'in_progress'
    ],
    [
        'title' => 'Протестировать API',
        'description' => 'Проверить все endpoints на работоспособность',
        'status' => 'pending'
    ]
];

echo "<h2>Добавление тестовых задач</h2>";

foreach ($testTasks as $task) {
    $result = addTask($task);
    
    if ($result['code'] === 201 && $result['response']['success']) {
        echo "<p style='color: green;'>✅ Добавлена: {$task['title']}</p>";
    } else {
        echo "<p style='color: red;'>❌ Ошибка при добавлении: {$task['title']} - ";
        echo $result['response']['error'] ?? 'Unknown error';
        echo "</p>";
    }
}

echo "<p><a href='http://localhost/todo-api/tasks' target='_blank'>Посмотреть все задачи</a></p>";