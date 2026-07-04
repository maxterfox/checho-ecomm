<div class="container">
    <div class="page-header">
        <h1>Products</h1>
        <a href="<?= url('/admin/products/create') ?>" class="btn btn-primary">Add Product</a>
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
                <th>Slug</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= escape($product['id']) ?></td>
                        <td><?= escape($product['name']) ?></td>
                        <td><?= escape($product['slug']) ?></td>
                        <td><?= formatPrice($product['price']) ?></td>
                        <td><?= escape($product['stock']) ?></td>
                        <td>
                            <span class="badge badge-<?= $product['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= escape(ucfirst($product['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= url('/admin/products/edit/' . $product['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="<?= url('/admin/products/delete/' . $product['id']) ?>" method="POST" style="display:inline">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
