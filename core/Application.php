<?php

namespace core;

class Application
{
    protected string $uri;
    public Request $request;
    public Router $router;
    public static Application $app;
    protected array $container = [];

    public function __construct()
    {
        self::$app = $this;
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->request = new Request($this->uri);
        $this->router = new Router($this->request);
    }

    public function run(): void
    {
        echo $this->router->dispatch();
    }
}