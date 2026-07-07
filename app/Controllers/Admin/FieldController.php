<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Log;
use App\Core\Request;
use App\Core\Session;
use App\Helpers\Permission;

class FieldController extends Controller
{
    private array $supportedModules = ['products', 'categories', 'users'];

    public function index(): void
    {
        $modules = [];
        foreach ($this->supportedModules as $name) {
            $modules[] = [
                'name' => $name,
                'display_name' => ucfirst($name),
                'field_count' => count(Permission::getModuleFields($name)),
            ];
        }

        $this->view('admin/fields/index', [
            'modules' => $modules,
            'title' => 'Permisos de campos',
        ], 'admin');
    }

    public function edit(string $module): void
    {
        if (!in_array($module, $this->supportedModules, true)) {
            Session::setFlash('error', 'Módulo no compatible con permisos de campos.');
            $this->redirect('/admin/fields');
        }

        $roles = Permission::getAllRoles();
        $fields = Permission::getModuleFields($module);

        $fieldPermissions = [];
        foreach ($roles as $role) {
            $fieldPermissions[$role['id']] = Permission::getFieldPermissions((int) $role['id'], $module);
        }

        $this->view('admin/fields/edit', [
            'module' => $module,
            'roles' => $roles,
            'fields' => $fields,
            'fieldPermissions' => $fieldPermissions,
            'title' => 'Permisos de campos — ' . ucfirst($module),
        ], 'admin');
    }

    public function update(): void
    {
        if (!Request::validateCsrf(Request::post('csrf_token', ''))) {
            Session::setFlash('error', 'Token de formulario inválido.');
            $this->redirect('/admin/fields');
        }

        $module = Request::post('module', '');
        $permissions = Request::post('permissions', []);

        if (!in_array($module, $this->supportedModules, true)) {
            Session::setFlash('error', 'Módulo inválido.');
            $this->redirect('/admin/fields');
        }

        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $db->delete('role_field_permissions', 'module_name = :module_name', ['module_name' => $module]);

            foreach ($permissions as $roleId => $fields) {
                $roleId = (int) $roleId;
                foreach ($fields as $fieldName => $enabled) {
                    if ($enabled) {
                        $db->insert('role_field_permissions', [
                            'role_id' => $roleId,
                            'module_name' => $module,
                            'field_name' => $fieldName,
                            'can_edit' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            $db->commit();

            Log::write(Auth::id(), 'update', 'settings', "Updated field permissions for module: {$module}");
            Session::setFlash('success', 'Permisos de campos actualizados correctamente.');
        } catch (\Throwable $e) {
            $db->rollback();
            if (APP_DEBUG) throw $e;
            Session::setFlash('error', 'Error al actualizar permisos de campos.');
        }

        $this->redirect('/admin/fields/' . $module);
    }
}
