<?php

declare(strict_types=1);

namespace core;

class Request
{
    public string $uri;
    public string $rawUri;
    public string $path;
    public array $post;

    /**
     * Query-параметры, разобранные из URI.
     * Единственный источник истины для GET-параметров.
     * Поле $get удалено — оно дублировало $queryParams и могло расходиться с ним.
     */
    public array $queryParams;

    public function __construct(string $uri)
    {
        $this->rawUri = $uri;
        $this->uri    = trim(urldecode($uri), '/');
        $this->post   = $_POST;

        $this->parseUri();
    }

    public function json(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }

    public function header(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$key] ?? null;
    }

    protected function parseUri(): void
    {
        $parts      = explode('?', $this->rawUri, 2);
        $this->path = trim(urldecode($parts[0]), '/');

        if (isset($parts[1])) {
            parse_str($parts[1], $fromUri);
            $this->queryParams = $fromUri;
        } else {
            $this->queryParams = $_GET;
        }
    }

    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function get(string $name, mixed $default = null): ?string
    {
        $value = $this->queryParams[$name] ?? $default;
        return $value !== null ? (string) $value : null;
    }

    public function getPost(string $name, mixed $default = null): ?string
    {
        $value = $this->post[$name] ?? $default;
        return $value !== null ? (string) $value : null;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAllGet(): array
    {
        return $this->queryParams;
    }

    public function hasGet(string $name): bool
    {
        return isset($this->queryParams[$name]);
    }

    public function getInt(string $name, int $default = 0): int
    {
        $value = $this->get($name);
        return $value !== null ? (int) $value : $default;
    }
}