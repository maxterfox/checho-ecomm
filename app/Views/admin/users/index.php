<div class="container">
    <div class="page-header">
        <h1>Users</h1>
        <a href="<?= url('/admin/users/create') ?>" class="btn btn-primary">Add User</a>
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
                <th>Email</th>
                <th>Role</th>
                <th>Access Granted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= escape($user['id']) ?></td>
                        <td><?= escape($user['name']) ?></td>
                        <td><?= escape($user['email']) ?></td>
                        <td><?= escape($user['role_name'] ?? 'N/A') ?></td>
                        <td>
                            <span class="badge badge-<?= !empty($user['access_granted']) ? 'success' : 'danger' ?>">
                                <?= !empty($user['access_granted']) ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= url('/admin/users/edit/' . $user['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
