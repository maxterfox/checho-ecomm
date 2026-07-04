<div class="page-header">
    <h1>New Category</h1>
    <a href="<?= url('admin/categories') ?>" class="btn btn-secondary">Back</a>
</div>

<form action="<?= url('admin/categories') ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= old('name') ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"><?= old('description') ?></textarea>
    </div>

    <div class="form-group">
        <label for="parent_id">Parent Category</label>
        <select name="parent_id" id="parent_id">
            <option value="">No parent (top level)</option>
            <?php foreach ($parentCategories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= old('parent_id') == $cat['id'] ? 'selected' : '' ?>>
                    <?= escape($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Category</button>
    </div>
</form>
