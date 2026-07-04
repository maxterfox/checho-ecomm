<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();

        $featured = $db->fetchAll(
            "SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.status = 'active' AND p.deleted_at IS NULL
             ORDER BY p.id DESC
             LIMIT 8"
        );

        $categories = $db->fetchAll(
            "SELECT id, name, slug, description
             FROM categories
             WHERE status = 'active' AND deleted_at IS NULL
             ORDER BY name"
        );

        $primaryImages = [];
        if (!empty($featured)) {
            $ids = array_column($featured, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $images = $db->fetchAll(
                "SELECT product_id, image_path FROM product_images WHERE product_id IN ({$placeholders}) AND is_primary = 1",
                $ids
            );
            foreach ($images as $img) {
                $primaryImages[$img['product_id']] = $img['image_path'];
            }
        }

        $this->view('home', [
            'featured' => $featured,
            'categories' => $categories,
            'primaryImages' => $primaryImages,
            'title' => 'Welcome',
        ]);
    }
}
