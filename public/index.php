<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/env.php';

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/functions.php';

set_error_handler(function (int $severity, string $message, string $file, int $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function (Throwable $e) {
    if (APP_DEBUG) {
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
        echo '<title>Error - ' . APP_NAME . '</title>';
        echo '<style>body{font-family:monospace;background:#0a1628;color:#e0e0e0;padding:40px;max-width:900px;margin:0 auto}';
        echo 'h1{color:#ff6b00}.type{color:#00d4ff}.msg{color:#ff4444;font-size:1.1rem}.trace{background:#121e34;padding:16px;border-radius:8px;overflow-x:auto;font-size:0.85rem;line-height:1.7}';
        echo '.file{color:#8899aa;margin:8px 0 16px}.line{border-bottom:1px solid #2a2a3e;padding:4px 0}</style></head><body>';
        echo '<h1>' . APP_NAME . ' — Error</h1>';
        echo '<p class="type">' . get_class($e) . '</p>';
        echo '<p class="msg">' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p class="file">' . $e->getFile() . ':' . $e->getLine() . '</p>';
        echo '<div class="trace">';
        foreach ($e->getTrace() as $i => $frame) {
            $file = $frame['file'] ?? '[internal]';
            $line = $frame['line'] ?? '';
            $fn = $frame['function'] ?? '';
            $class = $frame['class'] ?? '';
            echo '<div class="line">#' . $i . ' ' . htmlspecialchars("{$file}({$line})") . ': ';
            echo htmlspecialchars($class ? "{$class}->{$fn}" : $fn) . '</div>';
        }
        echo '</div></body></html>';
        exit;
    }

    http_response_code(500);
    require __DIR__ . '/../app/Views/errors/500.php';
    exit;
});

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['QUERY_STRING'] ?? '');
