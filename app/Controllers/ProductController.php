<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class ProductController extends Controller
{
    private const PER_PAGE = 12;

    public function index(): void
    {
        $db = Database::getInstance();

        $categorySlug = Request::get('category', '');
        $query = Request::get('q', '');
        $sort = Request::get('sort', 'latest');
        $page = max(1, (int) Request::get('page', 1));
        $offset = ($page - 1) * self::PER_PAGE;

        $where = "WHERE p.status = 'active' AND p.deleted_at IS NULL";
        $params = [];
        $countParams = [];

        if ($categorySlug !== '') {
            $where .= " AND c.slug = :category";
            $params['category'] = $categorySlug;
            $countParams['category'] = $categorySlug;
        }

        if ($query !== '') {
            $where .= " AND p.name LIKE :query";
            $likeQuery = '%' . $query . '%';
            $params['query'] = $likeQuery;
            $countParams['query'] = $likeQuery;
        }

        $orderBy = match ($sort) {
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'name_asc' => 'p.name ASC',
            'name_desc' => 'p.name DESC',
            default => 'p.id DESC',
        };

        $countSql = "SELECT COUNT(*) as total FROM products p LEFT JOIN categories c ON c.id = p.category_id {$where}";
        $totalResult = $db->fetch($countSql, $countParams);
        $total = (int) ($totalResult['total'] ?? 0);
        $lastPage = max(1, (int) ceil($total / self::PER_PAGE));

        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                {$where}
                ORDER BY {$orderBy}
                LIMIT " . self::PER_PAGE . " OFFSET {$offset}";
        $products = $db->fetchAll($sql, $params);

        $primaryImages = [];
        if (!empty($products)) {
            $ids = array_column($products, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $images = $db->fetchAll(
                "SELECT product_id, image_path FROM product_images WHERE product_id IN ({$placeholders}) AND is_primary = 1",
                $ids
            );
            foreach ($images as $img) {
                $primaryImages[$img['product_id']] = $img['image_path'];
            }
        }

        $categories = $db->fetchAll(
            "SELECT id, name, slug FROM categories WHERE status = 'active' AND deleted_at IS NULL ORDER BY name"
        );

        $this->view('products/index', [
            'products' => $products,
            'primaryImages' => $primaryImages,
            'categories' => $categories,
            'currentCategory' => $categorySlug,
            'query' => $query,
            'sort' => $sort,
            'page' => $page,
            'lastPage' => $lastPage,
            'total' => $total,
            'title' => 'Products',
        ]);
    }

    public function show(string $slug): void
    {
        $db = Database::getInstance();

        $product = $db->fetch(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.slug = :slug AND p.status = 'active' AND p.deleted_at IS NULL",
            ['slug' => $slug]
        );

        if (!$product) {
            http_response_code(404);
            $this->view('errors/not-found', ['title' => 'Product Not Found', 'message' => 'This product does not exist or is no longer available.'], 'main');
            return;
        }

        $images = $db->fetchAll(
            "SELECT image_path, is_primary FROM product_images WHERE product_id = :id ORDER BY is_primary DESC, sort_order ASC",
            ['id' => $product['id']]
        );

        $related = $db->fetchAll(
            "SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.category_id = :cat_id AND p.id != :prod_id
               AND p.status = 'active' AND p.deleted_at IS NULL
             ORDER BY p.id DESC
             LIMIT 4",
            ['cat_id' => $product['category_id'], 'prod_id' => $product['id']]
        );

        $primaryImages = [];
        if (!empty($related)) {
            $ids = array_column($related, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $imgs = $db->fetchAll(
                "SELECT product_id, image_path FROM product_images WHERE product_id IN ({$placeholders}) AND is_primary = 1",
                $ids
            );
            foreach ($imgs as $img) {
                $primaryImages[$img['product_id']] = $img['image_path'];
            }
        }

        $this->view('products/show', [
            'product' => $product,
            'images' => $images,
            'related' => $related,
            'primaryImages' => $primaryImages,
            'title' => $product['name'],
        ]);
    }
}
