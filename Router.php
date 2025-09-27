<?php
class Router {
    private $routes = [];

    public function addRoute($method, $pattern, $callback) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = '#^' . $route['pattern'] . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }

        Response::error('Route not found', 404);
    }
}