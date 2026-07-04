<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(): void
    {
        if (!Auth::isLoggedIn()) {
            Session::setFlash('error', 'Please log in to access this area.');
            header('Location: /login');
            exit;
        }

        if (!Auth::hasAccess()) {
            Session::setFlash('error', 'Your account does not have admin access.');
            header('Location: /');
            exit;
        }

        if (!Auth::isAdmin()) {
            Session::setFlash('error', 'Admin privileges required.');
            header('Location: /');
            exit;
        }
    }
}
