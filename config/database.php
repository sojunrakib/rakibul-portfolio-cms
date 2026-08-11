<?php

declare(strict_types=1);

return [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1',
    'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306',
    'name' => $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'rakibul_portfolio',
    'user' => $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root',
    'pass' => $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];
