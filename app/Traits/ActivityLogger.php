<?php

namespace App\Traits;

use App\Core\Auth;
use App\Core\Database;

trait ActivityLogger
{
    public static function log(string $action, string $module, string $description, ?int $referenceId = null): void
    {
        $db = Database::getInstance();

        $db->insert('activity_logs', [
            'user_id' => Auth::id() ?? 0,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'reference_id' => $referenceId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
