<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function delete(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    private function addRoute(string $method, string $uri, array $action, array $middleware): void
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $queryString): void
    {
        $uri = $this->parseUri($queryString);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                foreach ($route['middleware'] as $middlewareDef) {
                    if (is_array($middlewareDef)) {
                        $mwClass = array_shift($middlewareDef);
                        $middleware = new $mwClass(...$middlewareDef);
                    } else {
                        $middleware = new $middlewareDef();
                    }
                    $middleware->handle();
                }

                [$controllerClass, $methodName] = $route['action'];
                $controller = new $controllerClass();
                $controller->$methodName(...$params);
                return;
            }
        }

        http_response_code(404);
        $this->loadView('errors/404');
    }

    private function parseUri(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === null || $path === '' || $path === false) {
            return '/';
        }

        return $path;
    }

    private function loadView(string $view): void
    {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        }
    }
}
