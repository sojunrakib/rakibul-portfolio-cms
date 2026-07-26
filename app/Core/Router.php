<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array|callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, array|callable $handler): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', '/' . trim($path, '/'));
        $this->routes[] = [$method, '#^' . $pattern . '$#', $handler];
    }

    public function dispatch(Request $request): mixed
    {
        foreach ($this->routes as [$method, $pattern, $handler]) {
            if ($method !== $request->method() || !preg_match($pattern, $request->path(), $matches)) {
                continue;
            }

            $params = array_filter($matches, static fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
            if (is_array($handler)) {
                [$class, $action] = $handler;
                return (new $class())->{$action}($request, ...array_values($params));
            }

            return $handler($request, ...array_values($params));
        }

        http_response_code(404);
        return view('public/404', ['title' => 'Page not found']);
    }
}
