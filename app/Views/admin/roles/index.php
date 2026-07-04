<div class="container">
    <div class="page-header">
        <h1>Roles</h1>
        <a href="<?= url('/admin/roles/create') ?>" class="btn btn-primary">Add Role</a>
    </div>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger"><?= flash('error') ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Modules</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roles)): ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= escape($role['id']) ?></td>
                        <td><?= escape($role['name']) ?></td>
                        <td>
                            <?php if (!empty($role['modules'])): ?>
                                <?= escape(implode(', ', (array)$role['modules'])) ?>
                            <?php else: ?>
                                <em>None</em>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= url('/admin/roles/edit/' . $role['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No roles found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
