<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;

final class AuthController
{
    public function showLogin(Request $request): string
    {
        if (Auth::check()) {
            Response::redirect('/admin');
        }

        return View::render('admin/login', ['title' => 'Admin Login'], 'layouts/admin-auth');
    }

    public function login(Request $request): never
    {
        if (!Csrf::verify((string) $request->input('_csrf'))) {
            flash('error', 'Security token expired.');
            Response::redirect('/admin/login');
        }

        $key = 'login_attempts_' . sha1($request->ip());
        $attempt = $_SESSION[$key] ?? ['count' => 0, 'until' => 0];
        if (($attempt['until'] ?? 0) > time()) {
            flash('error', 'Too many login attempts. Please wait a few minutes.');
            Response::redirect('/admin/login');
        }

        if (Auth::attempt(trim((string) $request->input('email')), (string) $request->input('password'))) {
            unset($_SESSION[$key]);
            Response::redirect('/admin');
        }

        $attempt['count'] = (int) ($attempt['count'] ?? 0) + 1;
        if ($attempt['count'] >= 5) {
            $attempt['until'] = time() + 300;
        }
        $_SESSION[$key] = $attempt;
        flash('error', 'Invalid email or password.');
        Response::redirect('/admin/login');
    }

    public function logout(Request $request): never
    {
        if (Csrf::verify((string) $request->input('_csrf'))) {
            Auth::logout();
        }

        Response::redirect('/admin/login');
    }
}
