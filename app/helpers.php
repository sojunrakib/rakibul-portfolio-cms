<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Csrf;

function app(string $key): mixed
{
    return App::get($key);
}

function view(string $template, array $data = [], ?string $layout = 'layouts/public'): string
{
    return \App\Core\View::render($template, $data, $layout);
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim((string) (app('config')['app_url'] ?? ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Csrf::token()) . '">';
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash(?string $key = null, mixed $value = null): mixed
{
    if ($key !== null && $value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }

    if ($key !== null) {
        $message = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $message;
    }

    $all = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $all;
}

function setting(array $settings, string $key, mixed $default = ''): mixed
{
    return $settings[$key] ?? $default;
}

function storage_url(?string $path): string
{
    if (!$path) {
        return '';
    }
    return url('uploads/' . ltrim($path, '/'));
}
