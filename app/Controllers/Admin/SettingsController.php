<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->view('admin/settings/index', [
            'title' => 'Settings',
        ], 'admin');
    }
}
