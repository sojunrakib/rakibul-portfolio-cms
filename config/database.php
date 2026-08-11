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

$envAny = static function (array $keys, mixed $default = null) use ($env): mixed {
    foreach ($keys as $key) {
        $value = $env($key, null);
        if ($value !== null && $value !== '') {
            return $value;
        }
    }

    return $default;
};

$databaseUrl = $envAny([
    'DATABASE_URL',
    'DATABASE_PRIVATE_URL',
    'MYSQL_URL',
    'MYSQL_URI',
    'AIVEN_DATABASE_URL',
    'AIVEN_MYSQL_URL',
    'JAWSDB_URL',
    'CLEARDB_DATABASE_URL',
], '');
$urlConfig = [];
if (is_string($databaseUrl) && $databaseUrl !== '') {
    $parts = parse_url($databaseUrl);
    if (is_array($parts)) {
        $query = [];
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        $urlConfig = [
            'host' => $parts['host'] ?? null,
            'port' => isset($parts['port']) ? (string) $parts['port'] : null,
            'name' => isset($parts['path']) ? ltrim($parts['path'], '/') : null,
            'user' => isset($parts['user']) ? rawurldecode($parts['user']) : null,
            'pass' => isset($parts['pass']) ? rawurldecode($parts['pass']) : null,
            'ssl' => isset($query['ssl-mode']) && strtoupper((string) $query['ssl-mode']) !== 'DISABLED',
        ];
    }
}

$sslValue = $envAny(['DB_SSL', 'MYSQL_SSL', 'MYSQL_SSL_MODE'], $urlConfig['ssl'] ?? false);
$sslEnabled = is_string($sslValue)
    ? in_array(strtoupper($sslValue), ['1', 'TRUE', 'YES', 'ON', 'REQUIRED', 'VERIFY_CA', 'VERIFY_IDENTITY'], true)
    : filter_var($sslValue, FILTER_VALIDATE_BOOLEAN);

return [
    'host' => $envAny(['DB_HOST', 'MYSQL_HOST', 'MYSQLHOST'], $urlConfig['host'] ?? '127.0.0.1'),
    'port' => $envAny(['DB_PORT', 'MYSQL_PORT', 'MYSQLPORT'], $urlConfig['port'] ?? '3306'),
    'name' => $envAny(['DB_NAME', 'MYSQL_DATABASE', 'MYSQLDATABASE'], $urlConfig['name'] ?? 'rakibul_portfolio'),
    'user' => $envAny(['DB_USER', 'MYSQL_USER', 'MYSQLUSER'], $urlConfig['user'] ?? 'root'),
    'pass' => $envAny(['DB_PASS', 'DB_PASSWORD', 'MYSQL_PASSWORD', 'MYSQLPASSWORD'], $urlConfig['pass'] ?? ''),
    'charset' => 'utf8mb4',
    'ssl' => $sslEnabled,
    'ssl_ca' => $envAny(['DB_SSL_CA', 'MYSQL_SSL_CA'], null),
];
