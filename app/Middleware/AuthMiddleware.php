<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::isLoggedIn()) {
            Session::setFlash('error', 'Please log in to access this area.');
            header('Location: /login');
            exit;
        }

        if (!Auth::hasAccess()) {
            Session::setFlash('error', 'Your account does not have access to the system.');
            header('Location: /');
            exit;
        }
    }
}
