<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;
use App\Models\Role;
use App\Traits\ActivityLogger;

class UserController extends Controller
{
    use ActivityLogger;

    public function index(): void
    {
        $users = User::all();
        $this->view('admin/users/index', ['users' => $users], 'admin');
    }

    public function create(): void
    {
        $roles = Role::all();
        $this->view('admin/users/create', ['roles' => $roles], 'admin');
    }

    public function store(): void
    {
        $data = [
            'name' => Request::post('name'),
            'email' => Request::post('email'),
            'password' => password_hash(Request::post('password'), PASSWORD_DEFAULT),
            'role_id' => (int) Request::post('role_id'),
            'access_granted' => Request::post('access_granted') ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $userId = User::create($data);

        if ($userId) {
            $this->log('create', MODULE_USERS, 'Created user: ' . $data['email'], $userId);
            Session::setFlash('success', 'User created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create user.');
        }

        $this->redirect('/admin/users');
    }

    public function edit(int $id): void
    {
        $user = User::find($id);
        $roles = Role::all();

        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/admin/users');
        }

        $this->view('admin/users/edit', [
            'user' => $user,
            'roles' => $roles,
        ], 'admin');
    }

    public function update(int $id): void
    {
        $data = [
            'name' => Request::post('name'),
            'email' => Request::post('email'),
            'role_id' => (int) Request::post('role_id'),
            'access_granted' => Request::post('access_granted') ? 1 : 0,
        ];

        $password = Request::post('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        User::update($id, $data);
        $this->log('update', MODULE_USERS, 'Updated user ID: ' . $id, $id);
        Session::setFlash('success', 'User updated successfully.');
        $this->redirect('/admin/users');
    }
}
