<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;
use App\Helpers\Permission;

class PermissionMiddleware
{
    private string $module;
    private string $requiredLevel;

    public function __construct(string $module, string $requiredLevel = 'view')
    {
        $this->module = $module;
        $this->requiredLevel = $requiredLevel;
    }

    public function handle(): void
    {
        if (!Auth::isLoggedIn()) {
            Session::setFlash('error', 'Please log in to access this area.');
            header('Location: /login');
            exit;
        }

        $user = Auth::user();
        $roleId = (int) ($user['role_id'] ?? 0);

        if (empty($roleId)) {
            Session::setFlash('error', 'Your account has no role assigned.');
            header('Location: /admin');
            exit;
        }

        if (!Permission::roleHasModule($roleId, $this->module)) {
            Session::setFlash('error', 'You do not have access to the ' . ucfirst(str_replace('_', ' ', $this->module)) . ' module.');
            header('Location: /admin');
            exit;
        }

        if ($this->requiredLevel === 'modify' && !Permission::canModify($roleId, $this->module)) {
            Session::setFlash('error', 'You do not have permission to modify the ' . ucfirst(str_replace('_', ' ', $this->module)) . ' module.');
            header('Location: /admin');
            exit;
        }
    }
}
