<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/config.php';
$db = $config['db'];
$sqlFile = dirname(__DIR__) . '/database/schema.sql';

try {
    // Connexion directe à la base de données existante (avec le paramètre dbname)
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['port'], $db['name']),
        $db['user'],
        $db['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Lecture et nettoyage du fichier SQL
    $sql = file_get_contents($sqlFile);
    $sql = preg_replace('/^CREATE DATABASE.*?;/mi', '', $sql);
    $sql = preg_replace('/^USE .*?;/mi', '', $sql);

    // Exécution des requêtes SQL pour créer les tables
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if ($statement === '' || str_starts_with($statement, '--')) {
            continue;
        }
        $pdo->exec($statement);
    }

    echo 'Base ColocLomé installée avec succès. Comptes : etudiant@coloclome.tg et koffi.mensah@coloclome.tg (mot de passe : password).';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Installation impossible : ' . htmlspecialchars($e->getMessage());
}