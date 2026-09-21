<?php

return [
    'app' => [
        'name' => 'ColocLomé',
        'url' => getenv('APP_URL') ?: 'https://coloc-lome.wasmer.app/',
        'env' => getenv('APP_ENV') ?: 'production',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: 'db.fr-roub1.bengt.wasmernet.com',
        'port' => getenv('DB_PORT') ?: '20184',
        'name' => getenv('DB_NAME') ?: 'db_8a71fe16',
        'user' => getenv('DB_USER') ?: 'user_9663eaa1',
        'pass' => getenv('DB_PASS') ?: 'pw_0N8dz245xU4JrViaVdo12uHlP0rzRRq5',
        'charset' => 'utf8mb4',
    ],
];
