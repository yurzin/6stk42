<?php

namespace core;

class Request
{
    public string $uri;
    public string $rawUri;
    public string $path;
    public array $post;
    public array $get;
    public array $queryParams;

    public function __construct($uri)
    {
        $this->rawUri = $uri;
        $this->uri = trim(urldecode($uri), '/');
        $this->post = $_POST;
        $this->get = $_GET;
        $this->queryParams = $_GET;

        // Разбираем URI на путь и query параметры
        $this->parseUri();
    }

    protected function parseUri(): void
    {
        $parts = explode('?', $this->uri, 2);
        $this->path = trim($parts[0], '/');

        // Если есть query строка
        if (isset($parts[1])) {
            parse_str($parts[1], $this->queryParams);
            // Объединяем с $_GET на случай, если там уже были параметры
            $this->queryParams = array_merge($this->queryParams, $_GET);
        }
    }

    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): bool
    {
        return $this->getMethod() == 'GET';
    }

    public function isPost(): bool
    {
        return $this->getMethod() == 'POST';
    }

    public function get($name, $default = null): ?string
    {
        return $this->queryParams[$name] ?? $default;
    }

    public function post($name, $default = null): ?string
    {
        return $this->post[$name] ?? $default;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAllGet(): array
    {
        return $this->queryParams;
    }

    public function hasGet($name): bool
    {
        return isset($this->queryParams[$name]);
    }

    public function getInt($name, $default = 0): int
    {
        $value = $this->get($name);
        return $value !== null ? (int)$value : $default;
    }

    protected function removeQueryString(): string
    {
        return $this->path;
    }

}