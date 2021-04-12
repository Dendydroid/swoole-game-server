<?php

namespace App\Tcp\Core;

use App\Tcp\Controller\BaseController;
use App\Tcp\Middleware\BaseMiddleware;
use Generator;

class Route
{
    protected string $path;

    protected BaseController $controller;

    protected string $method;

    protected array $middlewares;

    public function __construct(string $path, BaseController $controller, string $method, array $middlewares = [])
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->method = $method;
        $this->middlewares = $middlewares;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    public function getController(): BaseController
    {
        return $this->controller;
    }

    public function setController(BaseController $controller): static
    {
        $this->controller = $controller;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function middlewareResults(array $data): Generator
    {
        /* @var BaseMiddleware $middleware */
        foreach ($this->middlewares as $middleware)
        {
            yield $middleware($data);
        }
    }
}