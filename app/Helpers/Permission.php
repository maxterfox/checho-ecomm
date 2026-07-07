<?php

namespace App\Helpers;

use App\Core\Database;

class Permission
{
    private static array $levelCache = [];
    private static array $fieldPermCache = [];

    public static function roleHasModule(int $roleId, string $moduleName): bool
    {
        return self::getLevel($roleId, $moduleName) !== null;
    }

    public static function canView(int $roleId, string $moduleName): bool
    {
        return self::getLevel($roleId, $moduleName) !== null;
    }

    public static function canModify(int $roleId, string $moduleName): bool
    {
        return self::getLevel($roleId, $moduleName) === 'modify';
    }

    public static function getLevel(int $roleId, string $moduleName): ?string
    {
        $key = $roleId . ':' . $moduleName;
        if (array_key_exists($key, self::$levelCache)) {
            return self::$levelCache[$key];
        }

        $db = Database::getInstance();
        $result = $db->fetch(
            "SELECT rp.level
             FROM role_permissions rp
             JOIN permissions p ON p.id = rp.permission_id
             WHERE rp.role_id = :role_id AND p.module_name = :module_name",
            ['role_id' => $roleId, 'module_name' => $moduleName]
        );

        self::$levelCache[$key] = $result['level'] ?? null;
        return self::$levelCache[$key];
    }

    public static function getRolePermissions(int $roleId): array
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll(
            "SELECT p.module_name, p.display_name, rp.level
             FROM role_permissions rp
             JOIN permissions p ON p.id = rp.permission_id
             WHERE rp.role_id = :role_id
             ORDER BY p.display_name",
            ['role_id' => $roleId]
        );

        $result = [];
        foreach ($rows as $row) {
            $result[$row['module_name']] = [
                'display_name' => $row['display_name'],
                'level' => $row['level'],
            ];
        }
        return $result;
    }

    public static function getAllModules(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT id, module_name, display_name
             FROM permissions
             ORDER BY display_name"
        );
    }

    public static function canEditField(int $roleId, string $module, string $field): bool
    {
        $level = self::getLevel($roleId, $module);

        if ($level === null) {
            return false;
        }

        if ($level === 'view') {
            return false;
        }

        $key = $roleId . ':' . $module . ':' . $field;
        if (array_key_exists($key, self::$fieldPermCache)) {
            return self::$fieldPermCache[$key];
        }

        $db = Database::getInstance();
        $result = $db->fetch(
            "SELECT can_edit FROM role_field_permissions
             WHERE role_id = :role_id AND module_name = :module_name AND field_name = :field_name",
            ['role_id' => $roleId, 'module_name' => $module, 'field_name' => $field]
        );

        self::$fieldPermCache[$key] = $result === null ? true : (bool) $result['can_edit'];
        return self::$fieldPermCache[$key];
    }

    public static function filterEditableFields(int $roleId, string $module, array $data): array
    {
        $filtered = [];
        $blocked = [];

        foreach ($data as $field => $value) {
            if (self::canEditField($roleId, $module, $field)) {
                $filtered[$field] = $value;
            } else {
                $blocked[] = $field;
            }
        }

        return ['filtered' => $filtered, 'blocked' => $blocked];
    }

    public static function getAllRoles(): array
    {
        $db = Database::getInstance();
        return $db->fetchAll('SELECT id, name, slug FROM roles ORDER BY name');
    }

    public static function getModuleFields(string $module): array
    {
        $fields = [
            'products' => [
                'name' => 'Nombre',
                'slug' => 'Slug',
                'description' => 'Descripción',
                'price' => 'Precio',
                'discount_price' => 'Precio de descuento',
                'compare_price' => 'Precio comparativo',
                'category_id' => 'Categoría',
                'stock' => 'Stock',
                'sku' => 'SKU',
                'main_image' => 'Imagen principal',
                'status' => 'Estado',
            ],
            'categories' => [
                'name' => 'Nombre',
                'slug' => 'Slug',
                'description' => 'Descripción',
                'parent_id' => 'Categoría padre',
                'status' => 'Estado',
            ],
            'users' => [
                'name' => 'Nombre',
                'email' => 'Correo electrónico',
                'password' => 'Contraseña',
                'role_id' => 'Rol',
                'access_granted' => 'Acceso concedido',
                'status' => 'Estado',
            ],
        ];

        return $fields[$module] ?? [];
    }

    public static function getFieldPermissions(int $roleId, string $module): array
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll(
            "SELECT field_name, can_edit FROM role_field_permissions
             WHERE role_id = :role_id AND module_name = :module_name",
            ['role_id' => $roleId, 'module_name' => $module]
        );

        $result = [];
        foreach ($rows as $row) {
            $result[$row['field_name']] = (bool) $row['can_edit'];
        }

        return $result;
    }
}
