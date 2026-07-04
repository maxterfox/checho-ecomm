<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Log;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\Permission;

class ProductController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();
        $products = $db->fetchAll(
            "SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.deleted_at IS NULL
             ORDER BY p.id DESC"
        );

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $canModify = Permission::canModify($roleId, 'products');

        $this->view('admin/products/index', [
            'products' => $products,
            'canModify' => $canModify,
            'title' => 'Products',
        ], 'admin');
    }

    public function create(): void
    {
        Session::remove('errors');
        Session::remove('old_input');

        $db = Database::getInstance();
        $categories = $db->fetchAll(
            "SELECT id, name FROM categories WHERE status = 'active' AND deleted_at IS NULL ORDER BY name"
        );

        $this->view('admin/products/create', [
            'categories' => $categories,
            'title' => 'New Product',
        ], 'admin');
    }

    public function store(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/products');
        }

        $name = trim(Request::post('name', ''));
        $slug = slugify($name);
        $description = trim(Request::post('description', ''));
        $price = Request::post('price', '');
        $discountPrice = Request::post('discount_price', '');
        $comparePrice = Request::post('compare_price', '');
        $categoryId = Request::post('category_id', '');
        $stock = Request::post('stock', '');
        $sku = trim(Request::post('sku', ''));
        $status = Request::post('status', 'draft');

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Product name is required.';
        } elseif (mb_strlen($name) > 300) {
            $errors['name'] = 'Product name must not exceed 300 characters.';
        }

        if ($description === '') {
            $errors['description'] = 'Description is required.';
        }

        if ($price === '' || !is_numeric($price) || (float) $price < 0) {
            $errors['price'] = 'A valid price is required.';
        }

        if ($discountPrice !== '' && (!is_numeric($discountPrice) || (float) $discountPrice < 0)) {
            $errors['discount_price'] = 'Discount price must be a valid positive number.';
        }

        if ($comparePrice !== '' && (!is_numeric($comparePrice) || (float) $comparePrice < 0)) {
            $errors['compare_price'] = 'Compare price must be a valid positive number.';
        }

        if ($categoryId === '' || (int) $categoryId <= 0) {
            $errors['category_id'] = 'Please select a category.';
        }

        if ($stock === '' || !is_numeric($stock) || (int) $stock < 0) {
            $errors['stock'] = 'Stock must be a valid non-negative number.';
        }

        if (!in_array($status, ['active', 'inactive', 'draft'], true)) {
            $errors['status'] = 'Invalid status selected.';
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            Session::set('old_input', Request::post());
            $this->redirect('/admin/products/create');
        }

        $db = Database::getInstance();

        $existingSlug = $db->fetch('SELECT id FROM products WHERE slug = :slug AND deleted_at IS NULL', ['slug' => $slug]);
        if ($existingSlug) {
            Session::set('old_input', Request::post());
            Session::setFlash('error', 'A product with this name already exists (duplicate slug).');
            $this->redirect('/admin/products/create');
        }

        if ($sku !== '') {
            $existingSku = $db->fetch('SELECT id FROM products WHERE sku = :sku AND deleted_at IS NULL', ['sku' => $sku]);
            if ($existingSku) {
                Session::set('old_input', Request::post());
                Session::setFlash('error', 'A product with this SKU already exists.');
                $this->redirect('/admin/products/create');
            }
        }

        $mainImage = null;
        if (Request::hasFile('main_image')) {
            $mainImage = $this->uploadImage(Request::file('main_image'));
            if ($mainImage === false) {
                $this->redirect('/admin/products/create');
            }
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => (float) $price,
            'discount_price' => $discountPrice !== '' ? (float) $discountPrice : null,
            'compare_price' => $comparePrice !== '' ? (float) $comparePrice : null,
            'category_id' => (int) $categoryId,
            'stock' => (int) $stock,
            'sku' => $sku !== '' ? $sku : null,
            'main_image' => $mainImage,
            'status' => $status,
        ];

        $userId = Auth::id() ?? 0;
        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $result = Permission::filterEditableFields($roleId, 'products', $data);

        foreach ($result['blocked'] as $field) {
            Log::write($userId, 'blocked_field', 'products', "Blocked edit on field '{$field}' for product creation");
        }

        if (empty($result['filtered'])) {
            Session::setFlash('error', 'You do not have permission to edit any product fields.');
            $this->redirect('/admin/products');
        }

        $result['filtered']['created_at'] = date('Y-m-d H:i:s');

        try {
            $productId = $db->insert('products', $result['filtered']);
            Log::write($userId, 'create', 'products', "Created product ID: {$productId} — {$name}", $productId);
            Session::setFlash('success', 'Product created successfully.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to create product.');
        }

        $this->redirect('/admin/products');
    }

    public function edit(int $id): void
    {
        Session::remove('errors');
        Session::remove('old_input');

        $db = Database::getInstance();
        $product = $db->fetch('SELECT * FROM products WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin/products');
        }

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $fieldPerms = Permission::getFieldPermissions($roleId, 'products');

        $categories = $db->fetchAll(
            "SELECT id, name FROM categories WHERE status = 'active' AND deleted_at IS NULL ORDER BY name"
        );

        $this->view('admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
            'fieldPerms' => $fieldPerms,
            'title' => 'Edit Product',
        ], 'admin');
    }

    public function update(int $id): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/products');
        }

        $db = Database::getInstance();
        $product = $db->fetch('SELECT * FROM products WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin/products');
        }

        $name = trim(Request::post('name', ''));
        $slug = slugify($name);
        $description = trim(Request::post('description', ''));
        $price = Request::post('price', '');
        $discountPrice = Request::post('discount_price', '');
        $comparePrice = Request::post('compare_price', '');
        $categoryId = Request::post('category_id', '');
        $stock = Request::post('stock', '');
        $sku = trim(Request::post('sku', ''));
        $status = Request::post('status', 'draft');

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Product name is required.';
        } elseif (mb_strlen($name) > 300) {
            $errors['name'] = 'Product name must not exceed 300 characters.';
        }

        if ($description === '') {
            $errors['description'] = 'Description is required.';
        }

        if ($price === '' || !is_numeric($price) || (float) $price < 0) {
            $errors['price'] = 'A valid price is required.';
        }

        if ($discountPrice !== '' && (!is_numeric($discountPrice) || (float) $discountPrice < 0)) {
            $errors['discount_price'] = 'Discount price must be a valid positive number.';
        }

        if ($comparePrice !== '' && (!is_numeric($comparePrice) || (float) $comparePrice < 0)) {
            $errors['compare_price'] = 'Compare price must be a valid positive number.';
        }

        if ($categoryId === '' || (int) $categoryId <= 0) {
            $errors['category_id'] = 'Please select a category.';
        }

        if ($stock === '' || !is_numeric($stock) || (int) $stock < 0) {
            $errors['stock'] = 'Stock must be a valid non-negative number.';
        }

        if (!in_array($status, ['active', 'inactive', 'draft'], true)) {
            $errors['status'] = 'Invalid status selected.';
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            $this->redirect('/admin/products/edit/' . $id);
        }

        $conflict = $db->fetch(
            'SELECT id FROM products WHERE slug = :slug AND id != :id AND deleted_at IS NULL',
            ['slug' => $slug, 'id' => $id]
        );
        if ($conflict) {
            Session::setFlash('error', 'Another product with this name already exists (duplicate slug).');
            $this->redirect('/admin/products/edit/' . $id);
        }

        if ($sku !== '') {
            $existingSku = $db->fetch(
                'SELECT id FROM products WHERE sku = :sku AND id != :id AND deleted_at IS NULL',
                ['sku' => $sku, 'id' => $id]
            );
            if ($existingSku) {
                Session::setFlash('error', 'Another product with this SKU already exists.');
                $this->redirect('/admin/products/edit/' . $id);
            }
        }

        $mainImage = $product['main_image'];
        if (Request::hasFile('main_image')) {
            $uploaded = $this->uploadImage(Request::file('main_image'));
            if ($uploaded !== false) {
                if ($mainImage && file_exists(__DIR__ . '/../../../public/storage/' . $mainImage)) {
                    unlink(__DIR__ . '/../../../public/storage/' . $mainImage);
                }
                $mainImage = $uploaded;
            } else {
                $this->redirect('/admin/products/edit/' . $id);
            }
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => (float) $price,
            'discount_price' => $discountPrice !== '' ? (float) $discountPrice : null,
            'compare_price' => $comparePrice !== '' ? (float) $comparePrice : null,
            'category_id' => (int) $categoryId,
            'stock' => (int) $stock,
            'sku' => $sku !== '' ? $sku : null,
            'main_image' => $mainImage,
            'status' => $status,
        ];

        $userId = Auth::id() ?? 0;
        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $result = Permission::filterEditableFields($roleId, 'products', $data);

        foreach ($result['blocked'] as $field) {
            Log::write($userId, 'blocked_field', 'products', "Blocked edit on field '{$field}' for product ID: {$id}", $id);
        }

        if (empty($result['filtered'])) {
            Session::setFlash('error', 'You do not have permission to edit any product fields.');
            $this->redirect('/admin/products/edit/' . $id);
        }

        try {
            $db->update('products', $result['filtered'], 'id = :id', ['id' => $id]);
            Log::write($userId, 'update', 'products', "Updated product ID: {$id} — {$name}", $id);
            Session::setFlash('success', 'Product updated successfully.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to update product.');
        }

        $this->redirect('/admin/products');
    }

    public function destroy(int $id): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/products');
        }

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        if (!Permission::canModify($roleId, 'products')) {
            Session::setFlash('error', 'You do not have permission to delete products.');
            $this->redirect('/admin/products');
        }

        $db = Database::getInstance();
        $product = $db->fetch('SELECT * FROM products WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('/admin/products');
        }

        try {
            $db->update('products', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
            Log::write(Auth::id() ?? 0, 'delete', 'products', "Deleted product ID: {$id} — {$product['name']}", $id);
            Session::setFlash('success', 'Product deleted.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to delete product.');
        }

        $this->redirect('/admin/products');
    }

    private function uploadImage(array $file): string|false
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            Session::setFlash('error', 'Image upload failed. Error code: ' . $file['error']);
            return false;
        }

        if (!in_array($file['type'], $allowedTypes, true)) {
            Session::setFlash('error', 'Image must be JPEG, PNG, WebP, or GIF.');
            return false;
        }

        if ($file['size'] > $maxSize) {
            Session::setFlash('error', 'Image must be under 2MB.');
            return false;
        }

        $ext = match ($file['type']) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'jpg',
        };

        $filename = 'products/' . bin2hex(random_bytes(16)) . '.' . $ext;
        $destPath = __DIR__ . '/../../../public/storage/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            Session::setFlash('error', 'Failed to save uploaded image.');
            return false;
        }

        return $filename;
    }
}
