<div class="page-header">
    <h1>Editar categoría</h1>
    <a href="<?= url('admin/categories') ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="<?= url('admin/categories/' . $category['id']) ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <?php $canEdit = fn($field) => !isset($fieldPerms[$field]) || $fieldPerms[$field]; ?>

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" value="<?= escape($category['name']) ?>" required>
    </div>

    <div class="form-group <?= !$canEdit('slug') ? 'disabled-field' : '' ?>">
        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug" value="<?= escape($category['slug']) ?>" <?= !$canEdit('slug') ? 'disabled' : '' ?>>
        <?php if (!$canEdit('slug')): ?><span class="field-note">Generado automáticamente, no editable</span><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="description">Descripción</label>
        <textarea name="description" id="description" rows="4"><?= escape($category['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label for="parent_id">Categoría padre</label>
        <select name="parent_id" id="parent_id">
            <option value="">Sin padre (nivel superior)</option>
            <?php foreach ($parentCategories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $category['parent_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= escape($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="active" <?= $category['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= $category['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar categoría</button>
    </div>
</form>
