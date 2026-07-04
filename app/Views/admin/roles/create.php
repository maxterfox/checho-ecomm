<div class="container">
    <div class="page-header">
        <h1>Create Role</h1>
    </div>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger"><?= flash('error') ?></div>
    <?php endif; ?>

    <form action="<?= url('/admin/roles/store') ?>" method="POST">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= escape(old('name')) ?>" required>
        </div>

        <fieldset class="form-fieldset">
            <legend>Module Permissions</legend>

            <?php if (!empty($modules)): ?>
                <?php foreach ($modules as $module): ?>
                    <div class="module-permissions">
                        <label class="checkbox-label">
                            <input type="checkbox" name="modules[]" value="<?= escape($module['name']) ?>"
                                <?= is_array(old('modules')) && in_array($module['name'], old('modules')) ? 'checked' : '' ?>>
                            <?= escape(ucfirst($module['name'])) ?>
                        </label>

                        <div class="permission-select">
                            <label for="perm_<?= escape($module['name']) ?>">Permission</label>
                            <select name="permissions[<?= escape($module['name']) ?>]" id="perm_<?= escape($module['name']) ?>" class="form-control">
                                <?php if (!empty($permissions)): ?>
                                    <?php foreach ($permissions as $perm): ?>
                                        <option value="<?= escape($perm) ?>" <?= old("permissions.{$module['name']}") === $perm ? 'selected' : '' ?>>
                                            <?= escape(ucfirst($perm)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No modules available.</p>
            <?php endif; ?>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= url('/admin/roles') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
