<?php

namespace App\Tcp\Core;

use Symfony\Component\Yaml\Yaml;

class RouteResolver
{
    private static string $routeFilePath = PROJECT_PATH . "/config/routes.yaml";

    public const CONTROLLER_TAG = 'controller';

    public const METHOD_TAG = 'method';

    private static function walkRoutes(array $routes, array &$result = [], string &$path = ""): array
    {
        foreach ($routes as $domain => $route) {
            $path .= "/$domain";
            if (isset($route[static::CONTROLLER_TAG]) && $route[static::METHOD_TAG]) {
                $controllerClass = $route[static::CONTROLLER_TAG];
                $result[] = new Route(
                    $path,
                    new $controllerClass,
                    $route[static::METHOD_TAG]
                );
                continue;
            }
            static::walkRoutes($route, $result, $path);
        }

        return $result;
    }

    public static function resolve(): array
    {
        $content = Yaml::parseFile(static::$routeFilePath);

        return static::walkRoutes($content['routes']);
    }
}