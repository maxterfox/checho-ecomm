<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class PermissionMiddleware
{
    private string $module;
    private string $permission;

    public function __construct(string $module, string $permission = 'view')
    {
        $this->module = $module;
        $this->permission = $permission;
    }

    public function handle(): void
    {
        if (!Auth::canAccessModule($this->module)) {
            Session::setFlash('error', 'You do not have access to this module.');
            header('Location: /admin/dashboard');
            exit;
        }

        if ($this->permission === 'modify' && !Auth::canModifyModule($this->module)) {
            Session::setFlash('error', 'You do not have permission to modify this module.');
            header('Location: /admin/dashboard');
            exit;
        }
    }
}
