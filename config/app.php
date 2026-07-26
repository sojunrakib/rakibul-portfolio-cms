<?php

declare(strict_types=1);

return [
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'app_url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/'),
    'key' => $_ENV['APP_KEY'] ?? 'change-this-long-random-session-secret',
    'mail_from' => $_ENV['MAIL_FROM'] ?? 'no-reply@example.com',
    'mail_to' => $_ENV['MAIL_TO'] ?? 'admin@example.com',
    'admin_email' => $_ENV['ADMIN_EMAIL'] ?? 'admin@example.com',
    'admin_password' => $_ENV['ADMIN_PASSWORD'] ?? 'ChangeMe123!',
];
