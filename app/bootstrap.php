<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Database;

$composer = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($composer)) {
    require $composer;
}

if (!class_exists(App::class, true)) {
    spl_autoload_register(static function (string $class): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }
        $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
        $path = __DIR__ . DIRECTORY_SEPARATOR . $relative . '.php';
        if (is_file($path)) {
            require $path;
        }
    });
}

if (!class_exists(App::class, false)) {
    require __DIR__ . '/Core/App.php';
}

if (!class_exists(Database::class, false)) {
    require __DIR__ . '/Core/Database.php';
}

$basePath = dirname(__DIR__);
$uploadTmpPath = $basePath . '/storage/uploads/tmp';
if (!is_dir($uploadTmpPath)) {
    mkdir($uploadTmpPath, 0775, true);
}
ini_set('upload_tmp_dir', $uploadTmpPath);

$sessionPath = $basePath . '/storage/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0775, true);
}

session_save_path($sessionPath);
session_name('rakibul_portfolio_session');

$envPath = $basePath . '/.env';
if (is_file($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if (getenv($key) === false && !array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

$secureCookies = filter_var(
    $_ENV['APP_SECURE_COOKIES'] ?? getenv('APP_SECURE_COOKIES') ?? false,
    FILTER_VALIDATE_BOOLEAN
);
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secureCookies,
    'httponly' => true,
    'samesite' => 'Strict',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

App::set('base_path', $basePath);
App::set('config', require $basePath . '/config/app.php');
App::set('db', new Database(require $basePath . '/config/database.php'));

require __DIR__ . '/helpers.php';
