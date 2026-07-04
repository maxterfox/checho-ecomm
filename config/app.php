<?php

define('APP_NAME', 'Urban Sports');
define('APP_URL', 'http://localhost:8000');
define('APP_ENV', 'development');
define('APP_DEBUG', true);

define('TIMEZONE', 'America/Argentina/Buenos_Aires');
date_default_timezone_set(TIMEZONE);

define('SESSION_LIFETIME', 7200);

define('UPLOAD_PATH', __DIR__ . '/../public/uploads');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);
