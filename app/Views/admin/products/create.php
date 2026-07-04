<div class="container">
    <div class="page-header">
        <h1>Create Product</h1>
    </div>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger"><?= flash('error') ?></div>
    <?php endif; ?>

    <form action="<?= url('/admin/products/store') ?>" method="POST">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= escape(old('name')) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5"><?= escape(old('description')) ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" value="<?= escape(old('price')) ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">-- Select Category --</option>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= escape($category['id']) ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                            <?= escape($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="<?= escape(old('stock', 0)) ?>">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= url('/admin/products') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
