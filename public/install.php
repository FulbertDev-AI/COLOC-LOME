<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/config.php';
$db = $config['db'];
$sqlFile = dirname(__DIR__) . '/database/schema.sql';

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['port'], $db['name']),
        $db['user'],
        $db['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $usersTableExists = (bool) $pdo->query("SHOW TABLES LIKE 'users'")->fetchColumn();
    $hasUsers = $usersTableExists && (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;

    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new RuntimeException('Fichier database/schema.sql introuvable.');
    }

    // Le fournisseur crée déjà la base : seules les tables et le seed local sont traités.
    $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
    $sql = preg_replace('/^\s*(CREATE DATABASE|USE)\b.*?;\s*$/mi', '', $sql) ?? $sql;
    $sql = preg_replace('/^\s*DROP TABLE IF EXISTS .*?;\s*$/mi', '', $sql) ?? $sql;
    $sql = preg_replace('/CREATE TABLE\s+(?!IF NOT EXISTS)/i', 'CREATE TABLE IF NOT EXISTS ', $sql) ?? $sql;

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if ($statement === '' || preg_match('/^(SET FOREIGN_KEY_CHECKS|SET NAMES)/i', $statement)) {
            continue;
        }
        if ($hasUsers && preg_match('/^INSERT\s+INTO/i', $statement)) {
            continue;
        }
        $pdo->exec($statement);
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    echo $hasUsers
        ? 'La structure de la base est déjà installée.'
        : 'Base ColocLomé installée avec succès. Comptes : etudiant@coloclome.tg et koffi.mensah@coloclome.tg (mot de passe : password).';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Installation impossible : ' . htmlspecialchars($e->getMessage());
}