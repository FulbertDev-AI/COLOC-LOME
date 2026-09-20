<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function init(array $config): void
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $root = new PDO(
                sprintf('mysql:host=%s;port=%s;charset=%s', $config['host'], $config['port'], $config['charset']),
                $config['user'],
                $config['pass'],
                $options
            );
            $name = str_replace('`', '', $config['name']);
            $root->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $root->exec("USE `{$name}`");
            self::$pdo = $root;
            self::seedIfEmpty();
        } catch (PDOException $e) {
            self::$pdo = null;
            error_log('DB connection failed: ' . $e->getMessage());
        }
    }

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            throw new \RuntimeException('Connexion MySQL indisponible. Vérifiez que MySQL tourne et les identifiants dans config/config.php.');
        }

        return self::$pdo;
    }

    public static function connected(): bool
    {
        return self::$pdo !== null;
    }

    private static function seedIfEmpty(): void
    {
        $exists = self::$pdo->query("SHOW TABLES LIKE 'users'")->fetch();
        if ($exists) {
            $count = (int) self::$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
            if ($count > 0) {
                return;
            }
        }

        $sqlFile = dirname(__DIR__, 2) . '/database/schema.sql';
        if (!is_file($sqlFile)) {
            return;
        }

        $sql = (string) file_get_contents($sqlFile);
        $sql = preg_replace('/^CREATE DATABASE.*?;/mi', '', $sql) ?? $sql;
        $sql = preg_replace('/^USE .*?;/mi', '', $sql) ?? $sql;

        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            if ($statement === '' || str_starts_with($statement, '--') || str_starts_with($statement, 'SET NAMES')) {
                continue;
            }
            self::$pdo->exec($statement);
        }
    }
}
