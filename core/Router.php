<?php

namespace core;

class Router
{
    protected array $routes = [];
    public array $route_params = [];


    public function __construct(
        protected Request $request,
    )
    {
    }

    public function get($path, $callback): self
    {
        $path = trim($path, '/');
        $this->routes[] = [
            'path' => "$path",
            'callback' => $callback,
            'method' => ['GET']
        ];
        return $this;
    }

    public function dispatch(): mixed
    {
        $path = $this->request->getPath();
        $route = $this->matchRoute($path);

        if (false === $route) {
            abort();
        }

        if (is_array($route['callback'])) {
            $route['callback'][0] = new $route['callback'][0];
        }

        return call_user_func($route['callback'], $this->request);
    }

    protected function matchRoute($path): mixed
    {
        foreach ($this->routes as $route) {
            if ($route['path'] === $path && in_array($this->request->getMethod(), $route['method'])) {
                return $route;
            }
        }
        return false;
    }
}