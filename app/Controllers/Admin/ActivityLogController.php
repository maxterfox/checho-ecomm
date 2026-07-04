<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $module = $_GET['module'] ?? '';

        $logs = $this->getLogs($page, $module);

        $modules = require __DIR__ . '/../../../config/permissions.php';

        $this->view('admin/activity-log/index', [
            'logs' => $logs,
            'modules' => $modules['modules'],
            'selectedModule' => $module,
        ], 'admin');
    }

    private function getLogs(int $page, string $module): array
    {
        if ($module) {
            $data = ActivityLog::findAllWhere('module', $module);
            return [
                'data' => $data,
                'total' => count($data),
                'page' => 1,
                'lastPage' => 1,
            ];
        }

        return ActivityLog::paginate($page, 20);
    }
}
