<div class="container">
    <div class="page-header">
        <h1>Create Role</h1>
    </div>

    <form action="<?= url('admin/roles') ?>" method="post" class="form-card">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" name="name" id="name" value="<?= escape(old('name')) ?>" required>
        </div>

        <h2>Module Permissions</h2>

        <?php if (!empty($modules)): ?>
            <?php foreach ($modules as $module): ?>
                <div class="module-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="modules[]" value="<?= escape($module['module_name']) ?>">
                        <?= escape($module['display_name']) ?>
                    </label>

                    <div class="level-select">
                        <select name="permissions[<?= escape($module['module_name']) ?>]">
                            <?php foreach ($permissionLevels as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No modules available.</p>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Role</button>
            <a href="<?= url('admin/roles') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
