<div class="page-header">
    <h1>Categories</h1>
    <?php if (\App\Helpers\Permission::canModify((int) (\App\Core\Auth::user()['role_id'] ?? 0), 'categories')): ?>
        <a href="<?= url('admin/categories/create') ?>" class="btn btn-primary">New Category</a>
    <?php endif; ?>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($categories)): ?>
            <tr><td colspan="5" class="empty">No categories found.</td></tr>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= escape($cat['name']) ?></td>
                    <td><?= escape($cat['slug']) ?></td>
                    <td><?= escape($cat['status']) ?></td>
                    <td class="actions">
                        <a href="<?= url('admin/categories/edit/' . $cat['id']) ?>" class="btn btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
