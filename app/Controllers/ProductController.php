<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(): void
    {
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;

        if ($category) {
            $products = Product::findAllWhere('category_id', (int) $category);
        } elseif ($search) {
            $products = Product::search($search);
        } else {
            $products = Product::all();
        }

        $categories = Category::all();

        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $category,
            'search' => $search,
        ]);
    }

    public function show(string $slug): void
    {
        $product = Product::findWhere('slug', $slug);

        if (!$product) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $relatedProducts = Product::findAllWhere('category_id', $product['category_id']);

        $this->view('products/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
