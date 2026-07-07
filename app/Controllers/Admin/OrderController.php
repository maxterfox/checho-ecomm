<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

class OrderController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();
        $orders = $db->fetchAll(
            "SELECT o.*, u.name AS user_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             WHERE o.deleted_at IS NULL
             ORDER BY o.created_at DESC
             LIMIT 50"
        );

        $this->view('admin/orders/index', [
            'orders' => $orders,
            'title' => 'Pedidos',
        ], 'admin');
    }
}
