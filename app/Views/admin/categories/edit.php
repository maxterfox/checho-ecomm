<div class="page-header">
    <h1>Edit Category</h1>
    <a href="<?= url('admin/categories') ?>" class="btn btn-secondary">Back</a>
</div>

<form action="<?= url('admin/categories/' . $category['id']) ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <?php $canEdit = fn($field) => !isset($fieldPerms[$field]) || $fieldPerms[$field]; ?>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= escape($category['name']) ?>" required>
    </div>

    <div class="form-group <?= !$canEdit('slug') ? 'disabled-field' : '' ?>">
        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug" value="<?= escape($category['slug']) ?>" <?= !$canEdit('slug') ? 'disabled' : '' ?>>
        <?php if (!$canEdit('slug')): ?><span class="field-note">Auto-generated, not editable</span><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"><?= escape($category['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label for="parent_id">Parent Category</label>
        <select name="parent_id" id="parent_id">
            <option value="">No parent (top level)</option>
            <?php foreach ($parentCategories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $category['parent_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= escape($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="active" <?= $category['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $category['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Category</button>
    </div>
</form>
