<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    public function __construct(private readonly array $routes)
    {
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = $uri === '' ? '/' : $uri;
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $pattern => $handler) {
            [$routeMethod, $routePath] = explode(' ', $pattern, 2);
            if (strtoupper($routeMethod) !== strtoupper($method)) {
                continue;
            }

            $regex = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $routePath);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->call($handler, $params);
                return;
            }
        }

        http_response_code(404);
        (new View())->render('errors/404', ['title' => 'Page introuvable']);
    }

    private function call(array $handler, array $params): void
    {
        [$class, $action] = $handler;
        $controller = new $class();
        $controller->{$action}(...array_values($params));
    }
}
