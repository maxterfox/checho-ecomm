<?php

define('APP_NAME', env('APP_NAME', 'Urban Sports'));
define('APP_URL', env('APP_URL', 'http://localhost:8000'));
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', env('APP_DEBUG', true));

define('TIMEZONE', 'America/Argentina/Buenos_Aires');
date_default_timezone_set(TIMEZONE);

define('SESSION_LIFETIME', 7200);
