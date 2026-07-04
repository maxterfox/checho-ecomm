<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Role;
use App\Traits\ActivityLogger;

class RoleController extends Controller
{
    use ActivityLogger;

    public function index(): void
    {
        $roles = Role::all();
        $this->view('admin/roles/index', ['roles' => $roles], 'admin');
    }

    public function create(): void
    {
        $modules = require __DIR__ . '/../../../config/permissions.php';
        $this->view('admin/roles/create', ['modules' => $modules['modules'], 'permissions' => $modules['permissions']], 'admin');
    }

    public function store(): void
    {
        $data = [
            'name' => Request::post('name'),
            'slug' => slugify(Request::post('name')),
            'modules' => json_encode(Request::post('modules', [])),
            'permissions' => json_encode(Request::post('permissions', [])),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $roleId = Role::create($data);

        if ($roleId) {
            $this->log('create', MODULE_ROLES, 'Created role: ' . $data['name'], $roleId);
            Session::setFlash('success', 'Role created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create role.');
        }

        $this->redirect('/admin/roles');
    }

    public function edit(int $id): void
    {
        $role = Role::find($id);
        $config = require __DIR__ . '/../../../config/permissions.php';

        if (!$role) {
            Session::setFlash('error', 'Role not found.');
            $this->redirect('/admin/roles');
        }

        $role['modules'] = json_decode($role['modules'] ?? '[]', true);
        $role['permissions'] = json_decode($role['permissions'] ?? '[]', true);

        $this->view('admin/roles/edit', [
            'role' => $role,
            'modules' => $config['modules'],
            'permissions' => $config['permissions'],
        ], 'admin');
    }

    public function update(int $id): void
    {
        $data = [
            'name' => Request::post('name'),
            'slug' => slugify(Request::post('name')),
            'modules' => json_encode(Request::post('modules', [])),
            'permissions' => json_encode(Request::post('permissions', [])),
        ];

        Role::update($id, $data);
        $this->log('update', MODULE_ROLES, 'Updated role ID: ' . $id, $id);
        Session::setFlash('success', 'Role updated successfully.');
        $this->redirect('/admin/roles');
    }
}
