<?php

declare(strict_types=1);

namespace App\Core;

final class App
{
    private static array $config = [];

    public function __construct(
        array $config,
        private readonly Router $router,
    ) {
        self::$config = $config;
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        $value = self::$config;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $this->path();

        if (!Database::connected()) {
            http_response_code(503);
            (new View())->render('errors/db', ['title' => 'Installation requise']);
            return;
        }

        try {
            $this->router->dispatch($method, $uri);
        } catch (\Throwable $e) {
            http_response_code(500);
            if ((self::config('app.env') ?: 'local') === 'local') {
                echo '<pre>' . e($e->getMessage()) . "\n" . e($e->getTraceAsString()) . '</pre>';
                return;
            }
            echo 'Une erreur est survenue.';
        }
    }

    private function path(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = base_path();
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base)) ?: '/';
        }

        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? '/' : $uri;
    }
}
