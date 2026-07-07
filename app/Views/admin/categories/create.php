<div class="page-header">
    <h1>Nueva categoría</h1>
    <a href="<?= url('admin/categories') ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="<?= url('admin/categories') ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" value="<?= old('name') ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Descripción</label>
        <textarea name="description" id="description" rows="4"><?= old('description') ?></textarea>
    </div>

    <div class="form-group">
        <label for="parent_id">Categoría padre</label>
        <select name="parent_id" id="parent_id">
            <option value="">Sin padre (nivel superior)</option>
            <?php foreach ($parentCategories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= old('parent_id') == $cat['id'] ? 'selected' : '' ?>>
                    <?= escape($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear categoría</button>
    </div>
</form>
