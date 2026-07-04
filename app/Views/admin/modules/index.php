<div class="container">
    <div class="page-header">
        <h1>Modules</h1>
    </div>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger"><?= flash('error') ?></div>
    <?php endif; ?>

    <form action="<?= url('/admin/modules/update') ?>" method="POST">
        <?= csrfField() ?>

        <?php if (!empty($modules)): ?>
            <?php foreach ($modules as $module): ?>
                <?php $moduleName = is_string($module) ? $module : $module['name']; ?>
                <fieldset class="form-fieldset">
                    <legend><?= escape(ucfirst($moduleName)) ?></legend>

                    <?php if (!empty($groupedFields[$moduleName])): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Modifiable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($groupedFields[$moduleName] as $field): ?>
                                    <tr>
                                        <td><?= escape($field['name']) ?></td>
                                        <td><?= escape($field['type'] ?? 'string') ?></td>
                                        <td>
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="modifiable[<?= escape($moduleName) ?>][]" value="<?= escape($field['name']) ?>"
                                                    <?= !empty($field['modifiable']) ? 'checked' : '' ?>>
                                                Modifiable
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">No fields defined for this module.</p>
                    <?php endif; ?>
                </fieldset>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No modules available.</p>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Modules</button>
        </div>
    </form>
</div>
