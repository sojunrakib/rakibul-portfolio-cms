<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return App::get('db')->first('SELECT id, name, email FROM admins WHERE id = ?', [$_SESSION['admin_id']]);
    }

    public static function attempt(string $email, string $password): bool
    {
        $admin = App::get('db')->first('SELECT * FROM admins WHERE email = ? AND is_active = 1', [$email]);
        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        App::get('db')->execute('UPDATE admins SET last_login_at = NOW() WHERE id = ?', [$admin['id']]);
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_id']);
        session_regenerate_id(true);
    }

    public static function require(): void
    {
        if (!self::check()) {
            Response::redirect('/admin/login');
        }
    }
}
