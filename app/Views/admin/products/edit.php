<div class="page-header">
    <h1>Editar producto</h1>
    <a href="<?= url('admin/products') ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="<?= url('admin/products/' . $product['id']) ?>" method="post" class="form-card" enctype="multipart/form-data">
    <?= csrfField() ?>

    <?php if (hasErrors()): ?>
        <div class="alert alert-danger">Corrige los errores a continuación.</div>
    <?php endif; ?>

    <?php $canEdit = fn($field) => !isset($fieldPerms[$field]) || $fieldPerms[$field]; ?>

    <div class="form-group <?= !$canEdit('name') ? 'disabled-field' : '' ?> <?= error('name') ? 'has-error' : '' ?>">
        <label for="name">Nombre <span class="required">*</span></label>
        <input type="text" name="name" id="name" value="<?= escape($product['name']) ?>" <?= !$canEdit('name') ? 'disabled' : '' ?> required>
        <?php if (error('name')): ?><span class="field-error"><?= error('name') ?></span><?php endif; ?>
    </div>

    <div class="form-group <?= !$canEdit('description') ? 'disabled-field' : '' ?> <?= error('description') ? 'has-error' : '' ?>">
        <label for="description">Descripción <span class="required">*</span></label>
        <textarea name="description" id="description" rows="6" <?= !$canEdit('description') ? 'disabled' : '' ?>><?= escape($product['description']) ?></textarea>
        <?php if (error('description')): ?><span class="field-error"><?= error('description') ?></span><?php endif; ?>
    </div>

    <div class="form-row">
        <div class="form-group <?= !$canEdit('price') ? 'disabled-field' : '' ?> <?= error('price') ? 'has-error' : '' ?>">
            <label for="price">Precio <span class="required">*</span></label>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?= $product['price'] ?>" <?= !$canEdit('price') ? 'disabled' : '' ?> required>
            <?php if (error('price')): ?><span class="field-error"><?= error('price') ?></span><?php endif; ?>
        </div>

        <div class="form-group <?= !$canEdit('discount_price') ? 'disabled-field' : '' ?> <?= error('discount_price') ? 'has-error' : '' ?>">
            <label for="discount_price">Precio de descuento</label>
            <input type="number" name="discount_price" id="discount_price" step="0.01" min="0" value="<?= $product['discount_price'] ?>" <?= !$canEdit('discount_price') ? 'disabled' : '' ?>>
            <?php if (error('discount_price')): ?><span class="field-error"><?= error('discount_price') ?></span><?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group <?= !$canEdit('compare_price') ? 'disabled-field' : '' ?> <?= error('compare_price') ? 'has-error' : '' ?>">
            <label for="compare_price">Precio comparativo</label>
            <input type="number" name="compare_price" id="compare_price" step="0.01" min="0" value="<?= $product['compare_price'] ?>" <?= !$canEdit('compare_price') ? 'disabled' : '' ?>>
            <?php if (error('compare_price')): ?><span class="field-error"><?= error('compare_price') ?></span><?php endif; ?>
        </div>

        <div class="form-group <?= !$canEdit('category_id') ? 'disabled-field' : '' ?> <?= error('category_id') ? 'has-error' : '' ?>">
            <label for="category_id">Categoría <span class="required">*</span></label>
            <select name="category_id" id="category_id" <?= !$canEdit('category_id') ? 'disabled' : '' ?>>
                <option value="">Seleccionar categoría</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (error('category_id')): ?><span class="field-error"><?= error('category_id') ?></span><?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group <?= !$canEdit('stock') ? 'disabled-field' : '' ?> <?= error('stock') ? 'has-error' : '' ?>">
            <label for="stock">Stock <span class="required">*</span></label>
            <input type="number" name="stock" id="stock" min="0" value="<?= $product['stock'] ?>" <?= !$canEdit('stock') ? 'disabled' : '' ?>>
            <?php if (error('stock')): ?><span class="field-error"><?= error('stock') ?></span><?php endif; ?>
        </div>

        <div class="form-group <?= !$canEdit('sku') ? 'disabled-field' : '' ?> <?= error('sku') ? 'has-error' : '' ?>">
            <label for="sku">SKU</label>
            <input type="text" name="sku" id="sku" value="<?= escape($product['sku'] ?? '') ?>" <?= !$canEdit('sku') ? 'disabled' : '' ?>>
            <?php if (error('sku')): ?><span class="field-error"><?= error('sku') ?></span><?php endif; ?>
        </div>
    </div>

    <div class="form-group <?= !$canEdit('slug') ? 'disabled-field' : '' ?>">
        <label for="slug">Slug</label>
        <input type="text" id="slug" value="<?= escape($product['slug']) ?>" disabled>
        <?php if (!$canEdit('slug')): ?><span class="field-note">Generado automáticamente desde el nombre</span><?php endif; ?>
    </div>

    <div class="form-group <?= !$canEdit('main_image') ? 'disabled-field' : '' ?>">
        <label for="main_image">Imagen principal</label>
        <?php if (!empty($product['main_image'])): ?>
            <div class="current-image">
                <img src="<?= asset('storage/' . $product['main_image']) ?>" alt="" class="preview-thumb">
                <span class="field-note">Imagen actual. Sube una nueva para reemplazarla.</span>
            </div>
        <?php endif; ?>
        <input type="file" name="main_image" id="main_image" accept="image/jpeg,image/png,image/webp,image/gif" <?= !$canEdit('main_image') ? 'disabled' : '' ?>>
        <span class="field-note">JPEG, PNG, WebP o GIF. Máx. 2MB. Déjalo vacío para mantener la actual.</span>
        <?php if (error('main_image')): ?><span class="field-error"><?= error('main_image') ?></span><?php endif; ?>
    </div>

    <div class="form-group <?= !$canEdit('status') ? 'disabled-field' : '' ?> <?= error('status') ? 'has-error' : '' ?>">
        <label for="status">Estado</label>
        <select name="status" id="status" <?= !$canEdit('status') ? 'disabled' : '' ?>>
            <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Borrador</option>
            <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
        </select>
        <?php if (!$canEdit('status')): ?><span class="field-note">Solo los administradores pueden cambiar el estado</span><?php endif; ?>
        <?php if (error('status')): ?><span class="field-error"><?= error('status') ?></span><?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar producto</button>
        <a href="<?= url('admin/products') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
