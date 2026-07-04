<div class="container">
    <div class="page-header">
        <h1>Edit Role</h1>
    </div>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger"><?= flash('error') ?></div>
    <?php endif; ?>

    <form action="<?= url('/admin/roles/update/' . $role['id']) ?>" method="POST">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= escape($role['name']) ?>" required>
        </div>

        <fieldset class="form-fieldset">
            <legend>Module Permissions</legend>

            <?php if (!empty($modules)): ?>
                <?php foreach ($modules as $module): ?>
                    <?php $moduleName = is_string($module) ? $module : $module['name']; ?>
                    <div class="module-permissions">
                        <label class="checkbox-label">
                            <input type="checkbox" name="modules[]" value="<?= escape($moduleName) ?>"
                                <?= !empty($role['modules']) && in_array($moduleName, $role['modules']) ? 'checked' : '' ?>>
                            <?= escape(ucfirst($moduleName)) ?>
                        </label>

                        <div class="permission-select">
                            <label for="perm_<?= escape($moduleName) ?>">Permission</label>
                            <select name="permissions[<?= escape($moduleName) ?>]" id="perm_<?= escape($moduleName) ?>" class="form-control">
                                <?php if (!empty($permissions)): ?>
                                    <?php foreach ($permissions as $perm): ?>
                                        <?php
                                        $selectedPerm = !empty($role['permissions'][$moduleName]) ? $role['permissions'][$moduleName] : '';
                                        ?>
                                        <option value="<?= escape($perm) ?>" <?= $selectedPerm === $perm ? 'selected' : '' ?>>
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
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= url('/admin/roles') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
