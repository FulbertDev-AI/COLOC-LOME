<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Router;
use App\Core\Session;

$config = require dirname(__DIR__) . '/config/config.php';
$routes = require dirname(__DIR__) . '/config/routes.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
    $path = __DIR__ . DIRECTORY_SEPARATOR . $relative . '.php';

    if (is_file($path)) {
        require $path;
    }
});

require __DIR__ . '/helpers.php';

Session::start();
Database::init($config['db']);
Auth::boot();

$router = new Router($routes);
$app = new App($config, $router);

return $app;
