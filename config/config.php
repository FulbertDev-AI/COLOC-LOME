<?php

return [
    'app' => [
        'name' => 'ColocLomé',
        'url' => getenv('APP_URL') ?: 'https://coloc-lome.wasmer.app',
        'env' => getenv('APP_ENV') ?: 'production',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: 'db.fr-roub1.bengt.wasmernet.com',
        'port' => getenv('DB_PORT') ?: '20184',
        'name' => getenv('DB_NAME') ?: 'db_8a71fe16',
        'user' => getenv('DB_USER') ?: 'user_64829065',
        'pass' => getenv('DB_PASS') ?: 'pw_irvHPChDUN6FGshjqFxEVKmN4C4z5Rn3',
        'charset' => 'utf8mb4',
    ],
];
