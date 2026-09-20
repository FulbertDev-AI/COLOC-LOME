<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public function render(string $template, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $viewFile = dirname(__DIR__) . '/Views/' . $template . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('Vue introuvable : ' . $template);
        }

        extract($data, EXTR_SKIP);
        $user = Auth::user();

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = dirname(__DIR__) . '/Views/' . $layout . '.php';
        require $layoutFile;
    }
}
