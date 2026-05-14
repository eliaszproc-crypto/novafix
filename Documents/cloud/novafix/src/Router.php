<?php
class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $url    = trim($_GET['url'] ?? '', '/');

        foreach ($this->routes[$method] ?? [] as $path => $handler) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', trim($path, '/'));
            if (preg_match("#^{$pattern}$#", $url, $matches)) {
                array_shift($matches);
                [$class, $method_name] = $handler;
                require_once SRC_PATH . "/controllers/{$class}.php";
                (new $class)->{$method_name}(...$matches);
                return;
            }
        }
        http_response_code(404);
        echo '404 - Strona nie istnieje';
    }
}
