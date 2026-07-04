<?php

namespace App\Core;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function all(): array
    {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " ORDER BY id DESC";
        return $instance->db->fetchAll($sql);
    }

    public static function find(int $id): ?array
    {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id";
        return $instance->db->fetch($sql, ['id' => $id]);
    }

    public static function findWhere(string $column, mixed $value): ?array
    {
        $instance = new static();
        $safeColumn = preg_replace('/[^a-zA-Z0-9_`]/', '', $column);
        $safeColumn = '`' . trim($safeColumn, '`') . '`';
        $sql = "SELECT * FROM " . static::$table . " WHERE {$safeColumn} = :value";
        return $instance->db->fetch($sql, ['value' => $value]);
    }

    public static function findAllWhere(string $column, mixed $value): array
    {
        $instance = new static();
        $safeColumn = preg_replace('/[^a-zA-Z0-9_`]/', '', $column);
        $safeColumn = '`' . trim($safeColumn, '`') . '`';
        $sql = "SELECT * FROM " . static::$table . " WHERE {$safeColumn} = :value ORDER BY id DESC";
        return $instance->db->fetchAll($sql, ['value' => $value]);
    }

    public static function create(array $data): int
    {
        $instance = new static();
        return $instance->db->insert(static::$table, $data);
    }

    public static function update(int $id, array $data): int
    {
        $instance = new static();
        return $instance->db->update(
            static::$table,
            $data,
            static::$primaryKey . " = :id",
            ['id' => $id]
        );
    }

    public static function delete(int $id): int
    {
        $instance = new static();
        return $instance->db->delete(
            static::$table,
            static::$primaryKey . " = :id",
            ['id' => $id]
        );
    }

    public static function count(): int
    {
        $instance = new static();
        $sql = "SELECT COUNT(*) as count FROM " . static::$table;
        $result = $instance->db->fetch($sql);
        return (int) ($result['count'] ?? 0);
    }

    public static function paginate(int $page = 1, int $perPage = 10): array
    {
        $instance = new static();
        $offset = (int) (($page - 1) * $perPage);
        $perPage = (int) $perPage;
        $sql = "SELECT * FROM " . static::$table . " ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $instance->db->query($sql);

        $total = static::count();
        $lastPage = max(1, (int) ceil($total / $perPage));

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => $lastPage,
        ];
    }
}
