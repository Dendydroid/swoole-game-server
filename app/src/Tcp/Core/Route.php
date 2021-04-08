<?php

namespace App\Tcp\Core;

use App\Tcp\Controller\BaseController;

class Route
{
    protected string $path;

    protected BaseController $controller;

    protected string $method;

    public function __construct(string $path, BaseController $controller, string $method)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->method = $method;
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
}