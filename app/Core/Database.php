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
            $pdo = new PDO(
                sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $config['host'], $config['port'], $config['name'], $config['charset']),
                $config['user'],
                $config['pass'],
                $options
            );
            self::$pdo = $pdo;
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
        $hasUsers = $exists && (int) self::$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;
        if ($hasUsers) {
            return;
        }

        $sqlFile = dirname(__DIR__, 2) . '/database/schema.sql';
        if (!is_file($sqlFile)) {
            return;
        }

        $sql = (string) file_get_contents($sqlFile);
        $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
        $sql = preg_replace('/^\s*(CREATE DATABASE|USE)\b.*?;\s*$/mi', '', $sql) ?? $sql;
        $sql = preg_replace('/^\s*DROP TABLE IF EXISTS .*?;\s*$/mi', '', $sql) ?? $sql;
        $sql = preg_replace('/CREATE TABLE\s+(?!IF NOT EXISTS)/i', 'CREATE TABLE IF NOT EXISTS ', $sql) ?? $sql;

        self::$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            if ($statement === '' || preg_match('/^(SET FOREIGN_KEY_CHECKS|SET NAMES)/i', $statement)) {
                continue;
            }
            if ($hasUsers && preg_match('/^INSERT\s+INTO/i', $statement)) {
                continue;
            }
            self::$pdo->exec($statement);
        }
        self::$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}
