<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Traits\ActivityLogger;

class ModuleController extends Controller
{
    use ActivityLogger;

    public function index(): void
    {
        $config = require __DIR__ . '/../../../config/permissions.php';
        $modules = $config['modules'];

        $db = Database::getInstance();
        $moduleFields = $db->fetchAll("SELECT * FROM module_fields ORDER BY module, field_name");

        $groupedFields = [];
        foreach ($moduleFields as $field) {
            $groupedFields[$field['module']][] = $field;
        }

        $this->view('admin/modules/index', [
            'modules' => $modules,
            'groupedFields' => $groupedFields,
            'permissions' => $config['permissions'],
        ], 'admin');
    }

    public function update(): void
    {
        $module = Request::post('module');
        $field = Request::post('field');
        $modifiable = Request::post('modifiable') ? 1 : 0;

        $db = Database::getInstance();

        $existing = $db->fetch(
            "SELECT id FROM module_fields WHERE module = :module AND field_name = :field",
            ['module' => $module, 'field' => $field]
        );

        if ($existing) {
            $db->update('module_fields', ['modifiable' => $modifiable], 'id = :id', ['id' => $existing['id']]);
        } else {
            $db->insert('module_fields', [
                'module' => $module,
                'field_name' => $field,
                'modifiable' => $modifiable,
            ]);
        }

        $this->log('update', MODULE_SETTINGS, "Updated field {$field} in module {$module}");
        Session::setFlash('success', 'Configuración de campos actualizada.');
        $this->redirect('/admin/modules');
    }
}
