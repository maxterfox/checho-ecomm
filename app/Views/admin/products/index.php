<div class="page-header">
    <h1>Products</h1>
    <?php if ($canModify): ?>
        <a href="<?= url('admin/products/create') ?>" class="btn btn-primary">New Product</a>
    <?php endif; ?>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($products)): ?>
            <tr><td colspan="8" class="empty">No products found.</td></tr>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td>
                        <?php if (!empty($p['main_image'])): ?>
                            <img src="<?= asset('storage/' . $p['main_image']) ?>" alt="" class="table-thumb">
                        <?php else: ?>
                            <span class="table-thumb-placeholder">&ndash;</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="table-link"><?= escape($p['name']) ?></a>
                    </td>
                    <td><?= escape($p['category_name'] ?? '-') ?></td>
                    <td>
                        <?= formatPrice($p['price']) ?>
                        <?php if (!empty($p['discount_price'])): ?>
                            <span class="table-badge sale">Sale</span>
                        <?php endif; ?>
                    </td>
                    <td><?= (int) $p['stock'] ?></td>
                    <td><span class="status-badge status-<?= $p['status'] === 'active' ? 'completed' : ($p['status'] === 'draft' ? 'pending' : 'cancelled') ?>"><?= escape($p['status']) ?></span></td>
                    <td class="actions-cell">
                        <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-ghost">Edit</a>
                        <?php if ($canModify): ?>
                            <form action="<?= url('admin/products/delete/' . $p['id']) ?>" method="post" class="inline-form" onsubmit="return confirm('Delete this product?')">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
