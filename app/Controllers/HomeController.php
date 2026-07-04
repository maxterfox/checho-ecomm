<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(): void
    {
        $products = Product::all();
        $categories = Category::all();

        $this->view('home', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
