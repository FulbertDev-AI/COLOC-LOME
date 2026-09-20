<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    private static ?array $user = null;

    public static function boot(): void
    {
        $id = Session::get('user_id');
        if (!$id || !Database::connected()) {
            return;
        }

        self::$user = (new User())->find((int) $id);
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        Session::put('user_id', (int) $user['id']);
        unset($user['password']);
        self::$user = $user;

        return true;
    }

    public static function login(array $user): void
    {
        Session::put('user_id', (int) $user['id']);
        unset($user['password']);
        self::$user = $user;
    }

    public static function logout(): void
    {
        Session::forget('user_id');
        self::$user = null;
        session_regenerate_id(true);
    }

    public static function requireRole(string $role): void
    {
        if (!self::check()) {
            header('Location: ' . url('/connexion'));
            exit;
        }

        if ((self::$user['role'] ?? '') !== $role) {
            header('Location: ' . url('/'));
            exit;
        }
    }
}
