<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class ActivityLogController extends Controller
{
    private const PER_PAGE = 20;

    public function index(): void
    {
        $db = Database::getInstance();

        $page = max(1, (int) Request::get('page', 1));
        $module = Request::get('module', '');
        $offset = ($page - 1) * self::PER_PAGE;

        $where = '';
        $params = [];
        $countParams = [];

        if ($module !== '') {
            $where = 'WHERE al.module = :module';
            $params['module'] = $module;
            $countParams['module'] = $module;
        }

        $countResult = $db->fetch(
            "SELECT COUNT(*) as total FROM activity_logs al {$where}",
            $countParams
        );
        $total = (int) ($countResult['total'] ?? 0);
        $lastPage = max(1, (int) ceil($total / self::PER_PAGE));

        $logs = $db->fetchAll(
            "SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             {$where}
             ORDER BY al.created_at DESC
             LIMIT " . self::PER_PAGE . " OFFSET {$offset}",
            $params
        );

        $modulesConfig = require __DIR__ . '/../../../config/permissions.php';

        $this->view('admin/activity-logs/index', [
            'logs' => [
                'data' => $logs,
                'total' => $total,
                'page' => $page,
                'lastPage' => $lastPage,
            ],
            'modules' => $modulesConfig['modules'],
            'selectedModule' => $module,
            'title' => 'Registro de actividades',
        ], 'admin');
    }
}
