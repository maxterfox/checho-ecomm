<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Product extends Model
{
    protected static string $table = 'products';

    public static function search(string $term): array
    {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " 
                WHERE name LIKE :term 
                OR description LIKE :term 
                ORDER BY id DESC";
        $stmt = $instance->db->query($sql, ['term' => '%' . $term . '%']);
        return $stmt->fetchAll();
    }

    public static function findByCategory(int $categoryId, int $limit = 10): array
    {
        return self::findAllWhere('category_id', $categoryId);
    }

    public static function getFeatured(int $limit = 8): array
    {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " 
                WHERE status = 'active' 
                ORDER BY created_at DESC 
                LIMIT " . (int) $limit;
        $stmt = $instance->db->query($sql);
        return $stmt->fetchAll();
    }
}
