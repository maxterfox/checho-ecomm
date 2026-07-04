<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Request;
use App\Core\Database;
use App\Core\Log;
use App\Helpers\Cart;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/');
        }

        $this->view('auth/login', ['title' => 'Login']);
        Session::remove('errors');
        Session::remove('old_input');
    }

    public function login(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/');
        }

        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token. Please try again.');
            $this->redirect('/login');
        }

        Session::remove('errors');
        Session::remove('old_input');

        $email = trim(Request::post('email', ''));
        $password = Request::post('password', '');

        $errors = [];

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if (!empty($errors)) {
            Session::set('old_input', ['email' => $email]);
            Session::set('errors', $errors);
            $this->redirect('/login');
        }

        try {
            $db = Database::getInstance();

            $user = $db->fetch(
                "SELECT u.*, r.name AS role_name, r.slug AS role_slug
                 FROM users u
                 LEFT JOIN roles r ON r.id = u.role_id
                 WHERE u.email = :email AND u.deleted_at IS NULL",
                ['email' => $email]
            );

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    Log::write(null, 'failed_login', 'auth', "Blocked — inactive account: {$email}");
                    Session::setFlash('error', 'Your account is inactive. Contact support.');
                    $this->redirect('/login');
                }

                Auth::login((int) $user['id'], [
                    'id' => (int) $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role_id' => (int) ($user['role_id'] ?? 0),
                    'role_name' => $user['role_name'] ?? 'Customer',
                    'role_slug' => $user['role_slug'] ?? 'customer',
                    'access_granted' => (bool) $user['access_granted'],
                ]);

                Cart::mergeGuestCartOnLogin();

                Log::write((int) $user['id'], 'login', 'auth', "User logged in: {$email}");

                Session::setFlash('success', 'Welcome back, ' . $user['name'] . '!');
                $this->redirect('/');
            }

            Log::write(null, 'failed_login', 'auth', "Failed login attempt for: {$email}");
            Session::setFlash('error', 'Invalid email or password.');
        } catch (\Throwable $e) {
            if (APP_DEBUG) {
                throw $e;
            }
            Session::setFlash('error', 'Login unavailable. Please try again later.');
        }

        Session::set('old_input', ['email' => $email]);
        $this->redirect('/login');
    }

    public function registerForm(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/');
        }

        $this->view('auth/register', ['title' => 'Register']);
        Session::remove('errors');
        Session::remove('old_input');
    }

    public function register(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect('/');
        }

        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Invalid form token. Please try again.');
            $this->redirect('/register');
        }

        Session::remove('errors');
        Session::remove('old_input');

        $name = trim(Request::post('name', ''));
        $email = trim(Request::post('email', ''));
        $password = Request::post('password', '');
        $passwordConfirm = Request::post('password_confirm', '');

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        } elseif (mb_strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters.';
        } elseif (mb_strlen($name) > 200) {
            $errors['name'] = 'Name must not exceed 200 characters.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (mb_strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }

        if ($passwordConfirm === '') {
            $errors['password_confirm'] = 'Please confirm your password.';
        } elseif ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            Session::set('old_input', ['name' => $name, 'email' => $email]);
            Session::set('errors', $errors);
            $this->redirect('/register');
        }

        try {
            $db = Database::getInstance();

            $existing = $db->fetch(
                'SELECT id FROM users WHERE email = :email AND deleted_at IS NULL',
                ['email' => $email]
            );

            if ($existing) {
                Session::set('old_input', ['name' => $name, 'email' => $email]);
                Session::setFlash('error', 'An account with this email already exists.');
                $this->redirect('/register');
            }

            $customerRole = $db->fetch("SELECT id, name, slug FROM roles WHERE slug = 'customer'");

            $userId = $db->insert('users', [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role_id' => $customerRole ? (int) $customerRole['id'] : null,
                'access_granted' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Auth::login($userId, [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'role_id' => $customerRole ? (int) $customerRole['id'] : 0,
                'role_name' => 'Customer',
                'role_slug' => 'customer',
                'access_granted' => false,
            ]);

            Cart::mergeGuestCartOnLogin();

            Log::write($userId, 'register', 'auth', "User registered: {$email}");

            Session::setFlash('success', 'Account created successfully! Welcome to ' . APP_NAME . '.');
            $this->redirect('/');
        } catch (\Throwable $e) {
            if (APP_DEBUG) {
                throw $e;
            }

            Session::set('old_input', ['name' => $name, 'email' => $email]);
            Session::setFlash('error', 'Registration failed. Please try again.');
            $this->redirect('/register');
        }
    }

    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            Log::write((int) $user['id'], 'logout', 'auth', "User logged out: {$user['email']}");
        }

        Auth::logout();
        Session::setFlash('success', 'You have been logged out.');
        $this->redirect('/login');
    }
}
