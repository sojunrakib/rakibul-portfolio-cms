<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Services\MailerService;

final class ContactController
{
    public function store(Request $request): never
    {
        if (!Csrf::verify((string) $request->input('_csrf'))) {
            flash('error', 'Security token expired. Please try again.');
            Response::redirect('/#contact');
        }

        $data = [
            'name' => trim((string) $request->input('name')),
            'email' => trim((string) $request->input('email')),
            'subject' => trim((string) $request->input('subject')),
            'message' => trim((string) $request->input('message')),
        ];

        $errors = Validator::required($data, ['name', 'email', 'subject', 'message']);
        if ($data['email'] !== '' && !Validator::email($data['email'])) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($errors) {
            $_SESSION['_old'] = $data;
            flash('error', implode(' ', $errors));
            Response::redirect('/#contact');
        }

        App::get('db')->execute(
            'INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)',
            [$data['name'], $data['email'], $data['subject'], $data['message'], $request->ip(), substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)]
        );

        (new MailerService())->notifyContact($data);
        unset($_SESSION['_old']);
        flash('success', 'Message sent. Rakibul will get back to you soon.');
        Response::redirect('/#contact');
    }
}
