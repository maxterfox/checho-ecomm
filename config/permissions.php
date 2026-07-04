<?php

define('ACCESS_GRANTED', 'access');

define('MODULE_PRODUCTS', 'products');
define('MODULE_CATEGORIES', 'categories');
define('MODULE_USERS', 'users');
define('MODULE_ROLES', 'roles');
define('MODULE_ORDERS', 'orders');
define('MODULE_ACTIVITY_LOG', 'activity_log');
define('MODULE_SETTINGS', 'settings');

define('PERMISSION_VIEW', 'view');
define('PERMISSION_MODIFY', 'modify');

return [
    'modules' => [
        MODULE_PRODUCTS => 'Products',
        MODULE_CATEGORIES => 'Categories',
        MODULE_USERS => 'Users',
        MODULE_ROLES => 'Roles',
        MODULE_ORDERS => 'Orders',
        MODULE_ACTIVITY_LOG => 'Activity Log',
        MODULE_SETTINGS => 'Settings',
    ],
    'permissions' => [
        PERMISSION_VIEW => 'View Only',
        PERMISSION_MODIFY => 'View & Modify',
    ],
];
