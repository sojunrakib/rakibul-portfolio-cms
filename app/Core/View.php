<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $template, array $data = [], ?string $layout = null): string
    {
        $views = App::get('base_path') . '/app/Views/';
        $file = $views . $template . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("View {$template} not found.");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        $content = ob_get_clean();

        if ($layout === null) {
            return $content;
        }

        ob_start();
        require $views . $layout . '.php';
        return ob_get_clean();
    }
}
