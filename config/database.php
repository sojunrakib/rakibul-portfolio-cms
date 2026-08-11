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
    'host' => $env('DB_HOST', '127.0.0.1'),
    'port' => $env('DB_PORT', '3306'),
    'name' => $env('DB_NAME', 'rakibul_portfolio'),
    'user' => $env('DB_USER', 'root'),
    'pass' => $env('DB_PASS', ''),
    'charset' => 'utf8mb4',
    'ssl' => filter_var($env('DB_SSL', false), FILTER_VALIDATE_BOOLEAN),
    'ssl_ca' => $env('DB_SSL_CA', null),
];
