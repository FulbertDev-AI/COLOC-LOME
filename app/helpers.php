<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function base_path(): string
{
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($script === '/' || $script === '.' || $script === '') {
        return '';
    }

    return rtrim($script, '/');
}

function url(string $path = '/'): string
{
    $path = '/' . ltrim($path, '/');
    return base_path() . ($path === '//' ? '/' : $path);
}

function asset(string $path): string
{
    return url('/assets/' . ltrim($path, '/'));
}

function listing_photo(?string $filename): string
{
    $name = $filename ?: 'listing-1a.jpg';

    return (string) preg_replace('/\.svg$/i', '.jpg', $name);
}

function config(string $key, mixed $default = null): mixed
{
    return App\Core\App::config($key, $default);
}

function old(string $key, mixed $default = ''): mixed
{
    $old = $_SESSION['_old'] ?? [];
    return $old[$key] ?? $default;
}

function flash(string $key): ?string
{
    $message = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $message;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function money(int|float $amount): string
{
    return number_format((float) $amount, 0, ',', ' ') . ' FCFA';
}

function current_user(): ?array
{
    return App\Core\Auth::user();
}

function is_student(): bool
{
    return (current_user()['role'] ?? null) === 'student';
}

function is_host(): bool
{
    return (current_user()['role'] ?? null) === 'host';
}

function active_nav(string $needle): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = base_path();
    if ($base && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base)) ?: '/';
    }

    return str_starts_with($uri, $needle) ? 'is-active' : '';
}
