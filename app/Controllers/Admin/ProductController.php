<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Traits\ActivityLogger;

class ProductController extends Controller
{
    use ActivityLogger;

    public function index(): void
    {
        $products = Product::all();
        $this->view('admin/products/index', ['products' => $products], 'admin');
    }

    public function create(): void
    {
        $categories = Category::all();
        $this->view('admin/products/create', ['categories' => $categories], 'admin');
    }

    public function store(): void
    {
        $data = [
            'name' => Request::post('name'),
            'slug' => slugify(Request::post('name')),
            'description' => Request::post('description'),
            'price' => (float) Request::post('price'),
            'category_id' => (int) Request::post('category_id'),
            'stock' => (int) Request::post('stock'),
            'status' => Request::post('status', 'active'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $productId = Product::create($data);

        if ($productId) {
            $this->log('create', MODULE_PRODUCTS, 'Created product: ' . $data['name'], $productId);
            Session::setFlash('success', 'Product created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create product.');
        }

        $this->redirect('/admin/products');
    }

    public function edit(int $id): void
    {
        $product = Product::find($id);
        $categories = Category::all();

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin/products');
        }

        $this->view('admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
        ], 'admin');
    }

    public function update(int $id): void
    {
        $data = [
            'name' => Request::post('name'),
            'slug' => slugify(Request::post('name')),
            'description' => Request::post('description'),
            'price' => (float) Request::post('price'),
            'category_id' => (int) Request::post('category_id'),
            'stock' => (int) Request::post('stock'),
            'status' => Request::post('status'),
        ];

        $updated = Product::update($id, $data);

        if ($updated) {
            $this->log('update', MODULE_PRODUCTS, 'Updated product ID: ' . $id, $id);
            Session::setFlash('success', 'Product updated successfully.');
        } else {
            Session::setFlash('error', 'Failed to update product.');
        }

        $this->redirect('/admin/products');
    }

    public function destroy(int $id): void
    {
        $product = Product::find($id);
        $deleted = Product::delete($id);

        if ($deleted) {
            $this->log('delete', MODULE_PRODUCTS, 'Deleted product: ' . ($product['name'] ?? ''), $id);
            Session::setFlash('success', 'Product deleted.');
        } else {
            Session::setFlash('error', 'Failed to delete product.');
        }

        $this->redirect('/admin/products');
    }
}
