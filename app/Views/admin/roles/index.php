<div class="container">
    <div class="page-header">
        <h1>Roles</h1>
        <a href="<?= url('admin/roles/create') ?>" class="btn btn-primary">Add Role</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Modules</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roles)): ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= escape($role['name']) ?></td>
                        <td><?= (int) $role['module_count'] ?> module(s)</td>
                        <td>
                            <a href="<?= url('admin/roles/edit/' . $role['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No roles found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= url('admin') ?>" class="btn btn-secondary">Back to Dashboard</a>
</div>
