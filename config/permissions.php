<?php

define('ACCESS_GRANTED', 'access');

define('MODULE_USERS', 'users');
define('MODULE_PRODUCTS', 'products');
define('MODULE_CATEGORIES', 'categories');
define('MODULE_ORDERS', 'orders');
define('MODULE_ROLES', 'roles');
define('MODULE_ACTIVITY_LOGS', 'activity_logs');
define('MODULE_SETTINGS', 'settings');

define('PERMISSION_VIEW', 'view');
define('PERMISSION_MODIFY', 'modify');

return [
    'modules' => [
        MODULE_USERS => 'Usuarios',
        MODULE_PRODUCTS => 'Productos',
        MODULE_CATEGORIES => 'Categorías',
        MODULE_ORDERS => 'Pedidos',
        MODULE_ROLES => 'Roles',
        MODULE_ACTIVITY_LOGS => 'Registro de actividades',
        MODULE_SETTINGS => 'Configuración',
    ],
    'permissions' => [
        PERMISSION_VIEW => 'Solo ver',
        PERMISSION_MODIFY => 'Ver y modificar',
    ],
];
