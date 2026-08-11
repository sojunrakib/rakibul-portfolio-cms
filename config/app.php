<?php

declare(strict_types=1);

$env = static function (string $key, mixed $default = null): mixed {
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }

    if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
        return $_ENV[$key];
    }

    return $default;
};

return [
    'env' => $env('APP_ENV', 'production'),
    'app_url' => rtrim((string) $env('APP_URL', 'http://localhost:8000'), '/'),
    'key' => $env('APP_KEY', 'change-this-long-random-session-secret'),
    'mail_from' => $env('MAIL_FROM', 'no-reply@example.com'),
    'mail_to' => $env('MAIL_TO', 'admin@example.com'),
    'admin_email' => $env('ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => $env('ADMIN_PASSWORD', 'ChangeMe123!'),
];
