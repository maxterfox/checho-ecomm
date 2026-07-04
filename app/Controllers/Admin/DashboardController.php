<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(): void
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalUsers' => User::count(),
            'totalOrders' => 0,
            'recentUsers' => [],
            'recentOrders' => [],
        ];

        $this->view('admin/dashboard/index', $data, 'admin');
    }
}
