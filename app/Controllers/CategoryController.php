<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show(string $slug): void
    {
        $category = Category::findWhere('slug', $slug);

        if (!$category) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $products = Product::findAllWhere('category_id', $category['id']);
        $categories = Category::all();

        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $category['id'],
            'category' => $category,
        ]);
    }
}
