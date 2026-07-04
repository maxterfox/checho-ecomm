<div class="page-header">
    <h1>Users</h1>
    <?php if (\App\Helpers\Permission::canModify((int) (\App\Core\Auth::user()['role_id'] ?? 0), 'users')): ?>
        <a href="<?= url('admin/users/create') ?>" class="btn btn-primary">New User</a>
    <?php endif; ?>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Access</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($users)): ?>
            <tr><td colspan="7" class="empty">No users found.</td></tr>
        <?php else: ?>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= escape($u['name']) ?></td>
                    <td><?= escape($u['email']) ?></td>
                    <td><?= escape($u['role_name']) ?></td>
                    <td><?= $u['access_granted'] ? 'Yes' : 'No' ?></td>
                    <td><?= escape($u['status']) ?></td>
                    <td class="actions">
                        <a href="<?= url('admin/users/edit/' . $u['id']) ?>" class="btn btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
