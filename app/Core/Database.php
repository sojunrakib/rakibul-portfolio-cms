<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private ?PDO $pdo = null;

    public function __construct(private readonly array $config)
    {
    }

    public function pdo(): PDO
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['name'],
            $this->config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $this->config['user'], $this->config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            $host = $this->config['host'] ?? 'unknown';
            $port = $this->config['port'] ?? 'unknown';
            $dbname = $this->config['name'] ?? 'unknown';

            throw new RuntimeException(
                sprintf(
                    'Database connection failed (mysql://%s:%s/%s): %s. Verify DB_HOST, DB_PORT, DB_NAME, DB_USER, and DB_PASS in the environment and import database/migrations.sql. On Render, 127.0.0.1:3306 only works if you bundle MySQL in the container; otherwise DB_HOST must point to an external database.',
                    $host,
                    $port,
                    $dbname,
                    $exception->getMessage()
                ),
                0,
                $exception
            );
        }

        return $this->pdo;
    }

    public function select(string $sql, array $params = []): array
    {
        $statement = $this->pdo()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function first(string $sql, array $params = []): ?array
    {
        $rows = $this->select($sql, $params);
        return $rows[0] ?? null;
    }

    public function execute(string $sql, array $params = []): bool
    {
        $statement = $this->pdo()->prepare($sql);
        return $statement->execute($params);
    }

    public function lastInsertId(): string
    {
        return $this->pdo()->lastInsertId();
    }
}
