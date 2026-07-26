<?php

declare(strict_types=1);

namespace App\Models;

final class CmsModel extends BaseModel
{
    public function rows(string $table, string $order = 'id', string $query = '', int $limit = 25, int $offset = 0): array
    {
        $allowedOrder = preg_match('/^[a-zA-Z0-9_]+$/', $order) ? $order : 'id';
        $sql = "SELECT * FROM {$table}";
        $params = [];

        if ($query !== '') {
            $sql .= " WHERE CONCAT_WS(' ', " . $this->columnsForSearch($table) . ') LIKE ?';
            $params[] = '%' . $query . '%';
        }

        $direction = in_array($allowedOrder, ['created_at', 'updated_at'], true) ? 'DESC' : 'ASC';
        $sql .= " ORDER BY {$allowedOrder} {$direction} LIMIT {$limit} OFFSET {$offset}";
        return $this->db()->select($sql, $params);
    }

    public function count(string $table, string $query = ''): int
    {
        if ($query === '') {
            return (int) $this->db()->first("SELECT COUNT(*) AS total FROM {$table}")['total'];
        }

        return (int) $this->db()->first(
            "SELECT COUNT(*) AS total FROM {$table} WHERE CONCAT_WS(' ', " . $this->columnsForSearch($table) . ') LIKE ?',
            ['%' . $query . '%']
        )['total'];
    }

    public function find(string $table, int $id): ?array
    {
        return $this->db()->first("SELECT * FROM {$table} WHERE id = ?", [$id]);
    }

    public function insert(string $table, array $data): void
    {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(', ', $columns), implode(', ', $placeholders));
        $this->db()->execute($sql, array_values($data));
    }

    public function update(string $table, int $id, array $data): void
    {
        $sets = array_map(static fn (string $column): string => "{$column} = ?", array_keys($data));
        $values = array_values($data);
        $values[] = $id;
        $this->db()->execute(sprintf('UPDATE %s SET %s WHERE id = ?', $table, implode(', ', $sets)), $values);
    }

    public function delete(string $table, int $id): void
    {
        $this->db()->execute("DELETE FROM {$table} WHERE id = ?", [$id]);
    }

    public function reorder(string $table, array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->db()->execute("UPDATE {$table} SET display_order = ? WHERE id = ?", [$index + 1, (int) $id]);
        }
    }

    private function columnsForSearch(string $table): string
    {
        $columns = $this->db()->select("SHOW COLUMNS FROM {$table}");
        $safe = array_filter(array_map(static fn (array $column): string => $column['Field'], $columns), static function (string $column): bool {
            return preg_match('/^[a-zA-Z0-9_]+$/', $column) === 1;
        });
        return implode(', ', $safe ?: ['id']);
    }
}
