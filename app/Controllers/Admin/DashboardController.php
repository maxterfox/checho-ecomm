<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

class DashboardController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();

        $stats = [
            'products' => (int) ($db->fetch("SELECT COUNT(*) as c FROM products WHERE deleted_at IS NULL")['c'] ?? 0),
            'active_products' => (int) ($db->fetch("SELECT COUNT(*) as c FROM products WHERE status = 'active' AND deleted_at IS NULL")['c'] ?? 0),
            'categories' => (int) ($db->fetch("SELECT COUNT(*) as c FROM categories WHERE deleted_at IS NULL")['c'] ?? 0),
            'users' => (int) ($db->fetch("SELECT COUNT(*) as c FROM users WHERE deleted_at IS NULL")['c'] ?? 0),
            'orders' => (int) ($db->fetch("SELECT COUNT(*) as c FROM orders WHERE deleted_at IS NULL")['c'] ?? 0),
            'pending_orders' => (int) ($db->fetch("SELECT COUNT(*) as c FROM orders WHERE status = 'pending' AND deleted_at IS NULL")['c'] ?? 0),
        ];

        $recentLogs = $db->fetchAll(
            "SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT 10"
        );

        $recentOrders = $db->fetchAll(
            "SELECT o.id, o.order_number, o.total, o.status, o.created_at, u.name AS user_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             WHERE o.deleted_at IS NULL
             ORDER BY o.created_at DESC
             LIMIT 5"
        );

        $roleId = (int) (Auth::user()['role_id'] ?? 0);
        $modules = [];
        foreach (['products', 'categories', 'users', 'orders'] as $m) {
            if (\App\Helpers\Permission::canView($roleId, $m)) {
                $modules[] = [
                    'name' => $m,
                    'can_modify' => \App\Helpers\Permission::canModify($roleId, $m),
                ];
            }
        }

        $this->view('admin/dashboard/index', [
            'stats' => $stats,
            'recentLogs' => $recentLogs,
            'recentOrders' => $recentOrders,
            'modules' => $modules,
            'title' => 'Panel principal',
        ], 'admin');
    }
}
