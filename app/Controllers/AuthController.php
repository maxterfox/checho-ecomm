<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/');
        }
        $this->view('auth/login');
    }

    public function login(): void
    {
        $email = Request::post('email');
        $password = Request::post('password');

        $user = User::findWhere('email', $email);

        if ($user && password_verify($password, $user['password'])) {
            Auth::login($user['id'], [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role_slug' => $user['role_slug'],
                'access_granted' => $user['access_granted'],
            ]);

            Session::setFlash('success', 'Welcome back, ' . $user['name'] . '!');
            $this->redirect('/');
        }

        Session::setFlash('error', 'Invalid email or password.');
        $this->redirect('/login');
    }

    public function registerForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/');
        }
        $this->view('auth/register');
    }

    public function register(): void
    {
        $data = [
            'name' => Request::post('name'),
            'email' => Request::post('email'),
            'password' => password_hash(Request::post('password'), PASSWORD_DEFAULT),
            'role_id' => 2,
            'access_granted' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $userId = User::create($data);

        if ($userId) {
            Auth::login($userId, [
                'id' => $userId,
                'name' => $data['name'],
                'email' => $data['email'],
                'role_slug' => 'customer',
                'access_granted' => 1,
            ]);

            Session::setFlash('success', 'Account created successfully!');
            $this->redirect('/');
        }

        Session::setFlash('error', 'Registration failed. Please try again.');
        $this->redirect('/register');
    }

    public function logout(): void
    {
        Auth::logout();
        Session::setFlash('success', 'You have been logged out.');
        $this->redirect('/');
    }
}
