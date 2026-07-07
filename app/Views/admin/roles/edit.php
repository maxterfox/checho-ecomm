<div class="container">
    <div class="page-header">
        <h1>Editar rol: <?= escape($role['name']) ?></h1>
    </div>

    <form action="<?= url('admin/roles/' . $role['id']) ?>" method="post" class="form-card">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name">Nombre del rol</label>
            <input type="text" name="name" id="name" value="<?= escape($role['name']) ?>" required>
        </div>

        <h2>Permisos de módulos</h2>

        <?php if (!empty($modules)): ?>
            <?php foreach ($modules as $module): ?>
                <?php $checked = isset($rolePermissions[$module['module_name']]); ?>
                <div class="module-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="modules[]" value="<?= escape($module['module_name']) ?>"
                               <?= $checked ? 'checked' : '' ?>>
                        <?= escape($module['display_name']) ?>
                    </label>

                    <div class="level-select">
                        <select name="permissions[<?= escape($module['module_name']) ?>]">
                            <?php foreach ($permissionLevels as $value => $label): ?>
                                <option value="<?= $value ?>"
                                    <?= $checked && $rolePermissions[$module['module_name']]['level'] === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No hay módulos disponibles.</p>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar rol</button>
            <a href="<?= url('admin/roles') ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
