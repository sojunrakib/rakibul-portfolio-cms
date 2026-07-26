<?php

declare(strict_types=1);

namespace App\Services;

final class SlugService
{
    public static function make(string $value): string
    {
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $value) ?? '', '-'));
        return $slug !== '' ? $slug : bin2hex(random_bytes(4));
    }
}
