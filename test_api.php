<?php
// Простой тест API
header('Content-Type: text/plain; charset=utf-8');
echo "=== Тест API ===\n\n";

// 1. Проверка файлов
echo "1. Проверка файлов:\n";
$files = [
    'config.php',
    'Database.php', 
    'Response.php',
    'Router.php',
    'Task.php',
    'TaskController.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file - не найден\n";
    }
}
echo "\n";

// 2. Проверка базы данных
echo "2. Проверка базы данных:\n";
try {
    require_once 'Database.php';
    $db = Database::getInstance()->getConnection();
    echo "✅ База данных подключена\n";
    
    echo "✅ SQLite работает\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка базы данных: " . $e->getMessage() . "\n";
}

echo "\n3. Тест завершен. Должен создаться файл tasks.db\n";