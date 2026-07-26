<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\App;

final class MailerService
{
    public function notifyContact(array $message): void
    {
        $config = App::get('config');
        $subject = 'Portfolio contact: ' . ($message['subject'] ?? 'New message');
        $body = "Name: {$message['name']}\nEmail: {$message['email']}\n\n{$message['message']}";
        $headers = 'From: ' . ($config['mail_from'] ?? 'no-reply@example.com');
        @mail($config['mail_to'] ?? '', $subject, $body, $headers);
    }
}
