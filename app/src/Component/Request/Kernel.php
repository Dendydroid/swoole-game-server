<?php

namespace App\Component\Request;

use App\Component\Application\GameApplication;
use App\Component\Exception\ExceptionFormatter;
use App\Tcp\Helper\Json;
use App\Tcp\Constant\Defaults;
use App\Tcp\Core\Route;
use App\Tcp\Core\RouteResolver;
use Swoole\WebSocket\Frame;
use Throwable;

class Kernel
{
    protected array $routes;

    public function __construct()
    {
        $this->routes = RouteResolver::resolve();
    }

    private function findRoute(array $request): ?Route
    {
        /* @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->getPath() === $request["route"]) {
                return $route;
            }
        }

        return null;
    }

    public function run(Frame $frame): void
    {
        $route = null;

        try {
            $request = Json::array($frame->data);

            $route = $this->findRoute($request);

            $method = $route->getMethod();

            $route->getController()->setFrame($frame);

            $route->getController()->$method($request["data"]);

        } catch (Throwable $exception) {
            error_log(ExceptionFormatter::toLogString($exception));
            if ($route === null) {
                GameApplication::app()->push($frame->fd, Json::encode(Defaults::ROUTE_NOT_FOUND));
            } else {
                GameApplication::app()->push($frame->fd, Json::encode([
                    "status" => 500,
                    "error" => ExceptionFormatter::toLogString($exception)
                ]));
            }
        }
    }
}