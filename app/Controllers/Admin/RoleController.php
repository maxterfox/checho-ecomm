<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Core\Log;
use App\Helpers\Permission;

class RoleController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();
        $roles = $db->fetchAll(
            "SELECT r.*,
                    (SELECT COUNT(*) FROM role_permissions rp WHERE rp.role_id = r.id) AS module_count
             FROM roles r
             ORDER BY r.name"
        );

        $this->view('admin/roles/index', [
            'roles' => $roles,
            'title' => 'Roles',
        ], 'admin');
    }

    public function create(): void
    {
        $modules = Permission::getAllModules();
        $permissionLevels = ['view' => 'View Only', 'modify' => 'View & Modify'];

        $this->view('admin/roles/create', [
            'modules' => $modules,
            'permissionLevels' => $permissionLevels,
            'title' => 'Create Role',
        ], 'admin');
    }

    public function store(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/roles');
        }

        $name = trim(Request::post('name', ''));
        $selectedModules = Request::post('modules', []);
        $permissions = Request::post('permissions', []);

        if ($name === '') {
            Session::setFlash('error', 'Role name is required.');
            $this->redirect('/admin/roles/create');
        }

        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $slug = slugify($name);

            $existing = $db->fetch(
                'SELECT id FROM roles WHERE slug = :slug',
                ['slug' => $slug]
            );

            if ($existing) {
                Session::setFlash('error', 'A role with this name already exists.');
                $this->redirect('/admin/roles/create');
            }

            $roleId = $db->insert('roles', [
                'name' => $name,
                'slug' => $slug,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!empty($selectedModules)) {
                foreach ($selectedModules as $moduleName) {
                    $perm = $db->fetch(
                        'SELECT id FROM permissions WHERE module_name = :module_name',
                        ['module_name' => $moduleName]
                    );

                    if ($perm) {
                        $level = $permissions[$moduleName] ?? 'view';
                        $level = in_array($level, ['view', 'modify'], true) ? $level : 'view';

                        $db->insert('role_permissions', [
                            'role_id' => $roleId,
                            'permission_id' => (int) $perm['id'],
                            'level' => $level,
                        ]);
                    }
                }
            }

            $db->commit();

            Log::write(Auth::id(), 'create', 'roles', "Created role: {$name}", $roleId);
            Session::setFlash('success', "Role '{$name}' created successfully.");
        } catch (\Throwable $e) {
            $db->rollback();
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to create role.');
        }

        $this->redirect('/admin/roles');
    }

    public function edit(int $id): void
    {
        $db = Database::getInstance();
        $role = $db->fetch('SELECT * FROM roles WHERE id = :id', ['id' => $id]);

        if (!$role) {
            Session::setFlash('error', 'Role not found.');
            $this->redirect('/admin/roles');
        }

        $modules = Permission::getAllModules();
        $rolePermissions = Permission::getRolePermissions($id);
        $permissionLevels = ['view' => 'View Only', 'modify' => 'View & Modify'];

        $this->view('admin/roles/edit', [
            'role' => $role,
            'modules' => $modules,
            'rolePermissions' => $rolePermissions,
            'permissionLevels' => $permissionLevels,
            'title' => 'Edit Role',
        ], 'admin');
    }

    public function update(int $id): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/roles');
        }

        $db = Database::getInstance();
        $role = $db->fetch('SELECT * FROM roles WHERE id = :id', ['id' => $id]);

        if (!$role) {
            Session::setFlash('error', 'Role not found.');
            $this->redirect('/admin/roles');
        }

        $name = trim(Request::post('name', ''));
        $selectedModules = Request::post('modules', []);
        $permissions = Request::post('permissions', []);

        if ($name === '') {
            Session::setFlash('error', 'Role name is required.');
            $this->redirect('/admin/roles/edit/' . $id);
        }

        try {
            $db->beginTransaction();

            $slug = slugify($name);

            $conflict = $db->fetch(
                'SELECT id FROM roles WHERE slug = :slug AND id != :id',
                ['slug' => $slug, 'id' => $id]
            );

            if ($conflict) {
                Session::setFlash('error', 'Another role with this name already exists.');
                $this->redirect('/admin/roles/edit/' . $id);
            }

            $db->update('roles', [
                'name' => $name,
                'slug' => $slug,
            ], 'id = :id', ['id' => $id]);

            $db->delete('role_permissions', 'role_id = :role_id', ['role_id' => $id]);

            if (!empty($selectedModules)) {
                foreach ($selectedModules as $moduleName) {
                    $perm = $db->fetch(
                        'SELECT id FROM permissions WHERE module_name = :module_name',
                        ['module_name' => $moduleName]
                    );

                    if ($perm) {
                        $level = $permissions[$moduleName] ?? 'view';
                        $level = in_array($level, ['view', 'modify'], true) ? $level : 'view';

                        $db->insert('role_permissions', [
                            'role_id' => $id,
                            'permission_id' => (int) $perm['id'],
                            'level' => $level,
                        ]);
                    }
                }
            }

            $db->commit();

            Log::write(Auth::id(), 'update', 'roles', "Updated role: {$name}", $id);
            Session::setFlash('success', "Role '{$name}' updated successfully.");
        } catch (\Throwable $e) {
            $db->rollback();
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to update role.');
        }

        $this->redirect('/admin/roles');
    }

}
