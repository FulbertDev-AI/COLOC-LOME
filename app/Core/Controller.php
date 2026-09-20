<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
        $this->assertCsrfOnPost();
    }

    protected function render(string $template, array $data = [], string $layout = 'layouts/main'): void
    {
        $this->view->render($template, $data, $layout);
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function back(): never
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header('Location: ' . $referer);
        exit;
    }

    protected function requireAuth(?string $role = null): array
    {
        if (!Auth::check()) {
            Session::flash('error', 'Connectez-vous pour continuer.');
            $this->redirect('/connexion');
        }

        if ($role) {
            Auth::requireRole($role);
        }

        return Auth::user();
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    private function assertCsrfOnPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        $token = $_POST['_csrf'] ?? '';
        if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
            http_response_code(419);
            echo 'Jeton de sécurité invalide.';
            exit;
        }
    }
}
