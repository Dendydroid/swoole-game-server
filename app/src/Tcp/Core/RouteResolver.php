<?php

namespace App\Tcp\Core;

use App\Tcp\Middleware\BaseMiddleware;
use Symfony\Component\Yaml\Yaml;

class RouteResolver
{
    private static string $routeFilePath = PROJECT_PATH . "/config/routes.yaml";

    public const CONTROLLER_TAG = 'controller';

    public const METHOD_TAG = 'method';

    public const MIDDLEWARES_TAG = 'middlewares';

    private static function walkRoutes(array $routes, array &$result = [], string &$path = "", bool $firstRow = true): array
    {
        foreach ($routes as $domain => $route) {
            if ($firstRow) {
                $path = "/$domain";
            } else {
                $path .= "/$domain";
            }
            if (isset($route[static::CONTROLLER_TAG]) && $route[static::METHOD_TAG]) {
                $middlewares = [];
                if (isset($route[static::MIDDLEWARES_TAG])) {
                    /* @var BaseMiddleware $middlewareClass */
                    foreach ($route[static::MIDDLEWARES_TAG] as $middlewareClass) {
                        $middlewares[] = new $middlewareClass;
                    }
                }
                $controllerClass = $route[static::CONTROLLER_TAG];
                $result[] = new Route(
                    $path,
                    new $controllerClass,
                    $route[static::METHOD_TAG],
                    $middlewares
                );
                $path = str_replace("/$domain", "", $path);
                continue;
            }

            static::walkRoutes($route, $result, $path, false);
        }

        return $result;
    }

    public static function resolve(): array
    {
        $content = Yaml::parseFile(static::$routeFilePath);

        return static::walkRoutes($content['routes']);
    }
}