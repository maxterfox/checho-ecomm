<?php

namespace App\Core;

class Log
{
    public static function write(
        ?int    $userId,
        string  $action,
        string  $module,
        string  $description,
        ?int    $referenceId = null
    ): void
    {
        $db = Database::getInstance();
        $db->insert('activity_logs', [
            'user_id' => $userId,
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
