<?php

namespace core;

class Router
{
    protected array $routes = [];
    public array $route_params = [];

    public function __construct(
        protected Request $request,
    ) {}

    public function get(string $path, array|callable $callback): self
    {
        return $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, array|callable $callback): self
    {
        return $this->addRoute('POST', $path, $callback);
    }

    public function dispatch(): mixed
    {
        $path  = $this->request->getPath();
        $route = $this->matchRoute($path);

        if ($route === false) {
            abort();
        }

        if (is_array($route['callback'])) {
            $route['callback'][0] = new $route['callback'][0];
        }

        return call_user_func($route['callback'], $this->request);
    }

    // ── private ────────────────────────────────────────────────────────────

    private function addRoute(string $method, string $path, array|callable $callback): self
    {
        // Нормализуем: убираем слеши с обоих концов, приводим к нижнему регистру
        $path = strtolower(trim($path, '/'));

        $this->routes[] = [
            'path'     => $path,
            'callback' => $callback,
            'method'   => $method,
        ];

        return $this;
    }

    private function matchRoute(string $path): array|false
    {
        // Нормализуем входящий путь так же, как при регистрации
        $path   = strtolower(trim($path, '/'));
        $method = $this->request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['path'] === $path && $route['method'] === $method) {
                return $route;
            }
        }

        return false;
    }
}