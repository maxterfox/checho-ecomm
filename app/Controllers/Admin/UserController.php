<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Log;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\Permission;

class UserController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();
        $users = $db->fetchAll(
            'SELECT u.*, r.name AS role_name
             FROM users u
             LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.deleted_at IS NULL
             ORDER BY u.id DESC'
        );

        $this->view('admin/users/index', ['users' => $users, 'title' => 'Users'], 'admin');
    }

    public function create(): void
    {
        $db = Database::getInstance();
        $roles = $db->fetchAll("SELECT id, name FROM roles ORDER BY name");

        $this->view('admin/users/create', ['roles' => $roles, 'title' => 'New User'], 'admin');
    }

    public function store(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/users');
        }

        $data = [
            'name' => Request::post('name', ''),
            'email' => Request::post('email', ''),
            'password' => password_hash(Request::post('password', ''), PASSWORD_BCRYPT, ['cost' => 10]),
            'role_id' => (int) Request::post('role_id', 0),
            'access_granted' => (int) Request::post('access_granted', 0),
            'status' => Request::post('status', 'active'),
        ];

        $userId = Auth::id() ?? 0;
        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $result = Permission::filterEditableFields($roleId, 'users', $data);

        foreach ($result['blocked'] as $field) {
            Log::write($userId, 'blocked_field', 'users', "Blocked edit on field '{$field}' for user creation");
        }

        if (empty($result['filtered'])) {
            Session::setFlash('error', 'You do not have permission to edit any user fields.');
            $this->redirect('/admin/users');
        }

        $result['filtered']['created_at'] = date('Y-m-d H:i:s');

        $db = Database::getInstance();

        $existing = $db->fetch('SELECT id FROM users WHERE email = :email', ['email' => $data['email']]);
        if ($existing) {
            Session::setFlash('error', 'A user with this email already exists.');
            $this->redirect('/admin/users/create');
        }

        try {
            $newId = $db->insert('users', $result['filtered']);
            Log::write($userId, 'create', 'users', "Created user ID: {$newId}", $newId);
            Session::setFlash('success', 'User created successfully.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to create user.');
        }

        $this->redirect('/admin/users');
    }

    public function edit(int $id): void
    {
        $db = Database::getInstance();
        $user = $db->fetch('SELECT * FROM users WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/admin/users');
        }

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $fieldPerms = Permission::getFieldPermissions($roleId, 'users');

        $roles = $db->fetchAll("SELECT id, name FROM roles ORDER BY name");

        $this->view('admin/users/edit', [
            'editUser' => $user,
            'roles' => $roles,
            'fieldPerms' => $fieldPerms,
            'title' => 'Edit User',
        ], 'admin');
    }

    public function update(int $id): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token.');
            $this->redirect('/admin/users');
        }

        $db = Database::getInstance();
        $user = $db->fetch('SELECT * FROM users WHERE id = :id AND deleted_at IS NULL', ['id' => $id]);

        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/admin/users');
        }

        $password = Request::post('password', '');

        $data = [
            'name' => Request::post('name', ''),
            'email' => Request::post('email', ''),
            'role_id' => (int) Request::post('role_id', 0),
            'access_granted' => (int) Request::post('access_granted', 0),
            'status' => Request::post('status', 'active'),
        ];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        }

        $userId = Auth::id() ?? 0;
        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $result = Permission::filterEditableFields($roleId, 'users', $data);

        foreach ($result['blocked'] as $field) {
            Log::write($userId, 'blocked_field', 'users', "Blocked edit on field '{$field}' for user ID: {$id}", $id);
        }

        if (empty($result['filtered'])) {
            Session::setFlash('error', 'You do not have permission to edit any user fields.');
            $this->redirect('/admin/users/edit/' . $id);
        }

        $existing = $db->fetch('SELECT id FROM users WHERE email = :email AND id != :id', ['email' => $data['email'], 'id' => $id]);
        if ($existing) {
            Session::setFlash('error', 'A user with this email already exists.');
            $this->redirect('/admin/users/edit/' . $id);
        }

        try {
            $db->update('users', $result['filtered'], 'id = :id', ['id' => $id]);
            Log::write($userId, 'update', 'users', "Updated user ID: {$id}", $id);
            Session::setFlash('success', 'User updated successfully.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to update user.');
        }

        $this->redirect('/admin/users');
    }

    public function destroy(int $id): void
    {
        $db = Database::getInstance();

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        if (!Permission::canModify($roleId, 'users')) {
            Session::setFlash('error', 'You do not have permission to delete users.');
            $this->redirect('/admin/users');
        }

        if ((int) Auth::id() === $id) {
            Session::setFlash('error', 'You cannot delete your own account.');
            $this->redirect('/admin/users');
        }

        try {
            $db->update('users', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
            Log::write(Auth::id() ?? 0, 'delete', 'users', "Deleted user ID: {$id}", $id);
            Session::setFlash('success', 'User deleted.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Failed to delete user.');
        }

        $this->redirect('/admin/users');
    }
}
