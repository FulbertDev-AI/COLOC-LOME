<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/config.php';
$db = $config['db'];
$sqlFile = dirname(__DIR__) . '/database/schema.sql';

try {
    $root = new PDO(
        sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $db['host'], $db['port']),
        $db['user'],
        $db['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $root->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '', $db['name']) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $root->exec('USE `' . str_replace('`', '', $db['name']) . '`');

    $sql = file_get_contents($sqlFile);
    $sql = preg_replace('/^CREATE DATABASE.*?;/mi', '', $sql);
    $sql = preg_replace('/^USE .*?;/mi', '', $sql);

    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if ($statement === '' || str_starts_with($statement, '--')) {
            continue;
        }
        $root->exec($statement);
    }

    echo 'Base ColocLomé installée. Comptes : etudiant@coloclome.tg et koffi.mensah@coloclome.tg (mot de passe : password).';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Installation impossible : ' . htmlspecialchars($e->getMessage());
}
