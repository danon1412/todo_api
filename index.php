<?php
// Включение отображения ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Настройка CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Обработка preflight запросов
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Автозагрузка классов
spl_autoload_register(function ($className) {
    $file = __DIR__ . '/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});

try {
    // Подключение необходимых файлов
    require_once __DIR__ . '/Database.php';
    require_once __DIR__ . '/Response.php';
    require_once __DIR__ . '/Router.php';
    require_once __DIR__ . '/Task.php';
    require_once __DIR__ . '/TaskController.php';

    // Инициализация роутера и контроллера
    $router = new Router();
    $taskController = new TaskController();

    // Регистрация маршрутов
    $router->addRoute('GET', 'tasks', [$taskController, 'getAllTasks']);
    $router->addRoute('GET', 'tasks/(\d+)', [$taskController, 'getTask']);
    $router->addRoute('POST', 'tasks', [$taskController, 'createTask']);
    $router->addRoute('PUT', 'tasks/(\d+)', [$taskController, 'updateTask']);
    $router->addRoute('DELETE', 'tasks/(\d+)', [$taskController, 'deleteTask']);

    // Обработка запроса
    $router->handleRequest();

} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), 500);
}