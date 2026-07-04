<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(): void
    {
        if (!Auth::isAdmin()) {
            Session::setFlash('error', 'You do not have permission to access the admin panel.');
            header('Location: /');
            exit;
        }
    }
}
