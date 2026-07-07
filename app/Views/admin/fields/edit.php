<div class="container">
    <div class="page-header">
        <h1>Permisos de campos: <?= escape(ucfirst($module)) ?></h1>
        <a href="<?= url('admin/fields') ?>" class="btn btn-secondary">Volver a módulos</a>
    </div>

    <form action="<?= url('admin/fields/update') ?>" method="post">
        <?= csrfField() ?>
        <input type="hidden" name="module" value="<?= escape($module) ?>">

        <table class="table">
            <thead>
                <tr>
                    <th>Campo</th>
                    <?php foreach ($roles as $role): ?>
                        <th class="role-col"><?= escape($role['name']) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fields as $fieldName => $fieldLabel): ?>
                    <tr>
                        <td><?= escape($fieldLabel) ?></td>
                        <?php foreach ($roles as $role): ?>
                            <?php $checked = !empty($fieldPermissions[$role['id']][$fieldName]); ?>
                            <td class="role-col">
                                <input type="hidden" name="permissions[<?= $role['id'] ?>][<?= escape($fieldName) ?>]" value="0">
                                <input type="checkbox"
                                       name="permissions[<?= $role['id'] ?>][<?= escape($fieldName) ?>]"
                                       value="1" <?= $checked ? 'checked' : '' ?>>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar permisos de campos</button>
        </div>
    </form>
</div>
