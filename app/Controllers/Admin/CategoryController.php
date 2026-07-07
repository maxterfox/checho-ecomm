<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Log;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\Permission;

class CategoryController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();
        $categories = $db->fetchAll(
            'SELECT * FROM categories WHERE deleted_at IS NULL ORDER BY name'
        );

        $this->view('admin/categories/index', ['categories' => $categories, 'title' => 'Categorías'], 'admin');
    }

    public function create(): void
    {
        $db = Database::getInstance();
        $parentCategories = $db->fetchAll("SELECT id, name FROM categories WHERE deleted_at IS NULL ORDER BY name");

        $this->view('admin/categories/create', ['parentCategories' => $parentCategories, 'title' => 'Nueva categoría'], 'admin');
    }

    public function store(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Token de formulario inválido.');
            $this->redirect('/admin/categories');
        }

        $data = [
            'name' => Request::post('name', ''),
            'slug' => slugify(Request::post('name', '')),
            'description' => Request::post('description', ''),
            'parent_id' => Request::post('parent_id') !== '' ? (int) Request::post('parent_id') : null,
            'status' => Request::post('status', 'active'),
        ];

        $userId = Auth::id() ?? 0;
        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $result = Permission::filterEditableFields($roleId, 'categories', $data);

        foreach ($result['blocked'] as $field) {
            Log::write($userId, 'blocked_field', 'categories', "Blocked edit on field '{$field}' for category creation");
        }

        if (empty($result['filtered'])) {
            Session::setFlash('error', 'No tienes permiso para editar campos de categorías.');
            $this->redirect('/admin/categories');
        }

        $result['filtered']['created_at'] = date('Y-m-d H:i:s');

        $db = Database::getInstance();
        try {
            $catId = $db->insert('categories', $result['filtered']);
            Log::write($userId, 'create', 'categories', "Created category ID: {$catId}", $catId);
            Session::setFlash('success', 'Categoría creada correctamente.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Error al crear la categoría.');
        }

        $this->redirect('/admin/categories');
    }

    public function edit(int $id): void
    {
        $db = Database::getInstance();
        $category = $db->fetch('SELECT * FROM categories WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$category) {
            Session::setFlash('error', 'Categoría no encontrada.');
            $this->redirect('/admin/categories');
        }

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $fieldPerms = Permission::getFieldPermissions($roleId, 'categories');

        $parentCategories = $db->fetchAll(
            "SELECT id, name FROM categories WHERE id != :id AND deleted_at IS NULL ORDER BY name",
            ['id' => $id]
        );

        $this->view('admin/categories/edit', [
            'category' => $category,
            'parentCategories' => $parentCategories,
            'fieldPerms' => $fieldPerms,
            'title' => 'Editar categoría',
        ], 'admin');
    }

    public function update(int $id): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Token de formulario inválido.');
            $this->redirect('/admin/categories');
        }

        $db = Database::getInstance();
        $category = $db->fetch('SELECT * FROM categories WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$category) {
            Session::setFlash('error', 'Categoría no encontrada.');
            $this->redirect('/admin/categories');
        }

        $data = [
            'name' => Request::post('name', ''),
            'slug' => slugify(Request::post('name', '')),
            'description' => Request::post('description', ''),
            'parent_id' => Request::post('parent_id') !== '' ? (int) Request::post('parent_id') : null,
            'status' => Request::post('status', 'active'),
        ];

        $userId = Auth::id() ?? 0;
        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $result = Permission::filterEditableFields($roleId, 'categories', $data);

        foreach ($result['blocked'] as $field) {
            Log::write($userId, 'blocked_field', 'categories', "Blocked edit on field '{$field}' for category ID: {$id}", $id);
        }

        if (empty($result['filtered'])) {
            Session::setFlash('error', 'No tienes permiso para editar campos de categorías.');
            $this->redirect('/admin/categories/edit/' . $id);
        }

        try {
            $db->update('categories', $result['filtered'], 'id = :id', ['id' => $id]);
            Log::write($userId, 'update', 'categories', "Updated category ID: {$id}", $id);
            Session::setFlash('success', 'Categoría actualizada correctamente.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Error al actualizar la categoría.');
        }

        $this->redirect('/admin/categories');
    }

    public function destroy(int $id): void
    {
        $db = Database::getInstance();

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        if (!Permission::canModify($roleId, 'categories')) {
            Session::setFlash('error', 'No tienes permiso para eliminar categorías.');
            $this->redirect('/admin/categories');
        }

        try {
            $db->update('categories', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
            Log::write(Auth::id() ?? 0, 'delete', 'categories', "Deleted category ID: {$id}", $id);
            Session::setFlash('success', 'Categoría eliminada.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Error al eliminar la categoría.');
        }

        $this->redirect('/admin/categories');
    }
}
