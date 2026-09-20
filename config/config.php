<?php

return [
    'app' => [
        'name' => 'ColocLomé',
        'url' => getenv('APP_URL') ?: 'http://localhost/COLOC/public',
        'env' => getenv('APP_ENV') ?: 'local',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'coloclome',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
];
