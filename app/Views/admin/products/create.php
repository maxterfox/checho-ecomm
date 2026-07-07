<div class="page-header">
    <h1>Nuevo producto</h1>
    <a href="<?= url('admin/products') ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="<?= url('admin/products') ?>" method="post" class="form-card" enctype="multipart/form-data">
    <?= csrfField() ?>

    <?php if (hasErrors()): ?>
        <div class="alert alert-danger">Corrige los errores a continuación.</div>
    <?php endif; ?>

    <div class="form-group <?= error('name') ? 'has-error' : '' ?>">
        <label for="name">Nombre <span class="required">*</span></label>
        <input type="text" name="name" id="name" value="<?= old('name') ?>" required>
        <?php if (error('name')): ?><span class="field-error"><?= error('name') ?></span><?php endif; ?>
    </div>

    <div class="form-group <?= error('description') ? 'has-error' : '' ?>">
        <label for="description">Descripción <span class="required">*</span></label>
        <textarea name="description" id="description" rows="6"><?= old('description') ?></textarea>
        <?php if (error('description')): ?><span class="field-error"><?= error('description') ?></span><?php endif; ?>
    </div>

    <div class="form-row">
        <div class="form-group <?= error('price') ? 'has-error' : '' ?>">
            <label for="price">Precio <span class="required">*</span></label>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?= old('price', '0') ?>" required>
            <?php if (error('price')): ?><span class="field-error"><?= error('price') ?></span><?php endif; ?>
        </div>

        <div class="form-group <?= error('discount_price') ? 'has-error' : '' ?>">
            <label for="discount_price">Precio de descuento</label>
            <input type="number" name="discount_price" id="discount_price" step="0.01" min="0" value="<?= old('discount_price') ?>">
            <?php if (error('discount_price')): ?><span class="field-error"><?= error('discount_price') ?></span><?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group <?= error('compare_price') ? 'has-error' : '' ?>">
            <label for="compare_price">Precio comparativo</label>
            <input type="number" name="compare_price" id="compare_price" step="0.01" min="0" value="<?= old('compare_price') ?>">
            <?php if (error('compare_price')): ?><span class="field-error"><?= error('compare_price') ?></span><?php endif; ?>
        </div>

        <div class="form-group <?= error('category_id') ? 'has-error' : '' ?>">
            <label for="category_id">Categoría <span class="required">*</span></label>
            <select name="category_id" id="category_id">
                <option value="">Seleccionar categoría</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                        <?= escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (error('category_id')): ?><span class="field-error"><?= error('category_id') ?></span><?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group <?= error('stock') ? 'has-error' : '' ?>">
            <label for="stock">Stock <span class="required">*</span></label>
            <input type="number" name="stock" id="stock" min="0" value="<?= old('stock', '0') ?>">
            <?php if (error('stock')): ?><span class="field-error"><?= error('stock') ?></span><?php endif; ?>
        </div>

        <div class="form-group <?= error('sku') ? 'has-error' : '' ?>">
            <label for="sku">SKU</label>
            <input type="text" name="sku" id="sku" value="<?= old('sku') ?>">
            <?php if (error('sku')): ?><span class="field-error"><?= error('sku') ?></span><?php endif; ?>
        </div>
    </div>

    <div class="form-group <?= error('main_image') ? 'has-error' : '' ?>">
        <label for="main_image">Imagen principal</label>
        <input type="file" name="main_image" id="main_image" accept="image/jpeg,image/png,image/webp,image/gif">
        <span class="field-note">JPEG, PNG, WebP o GIF. Máx. 2MB.</span>
        <?php if (error('main_image')): ?><span class="field-error"><?= error('main_image') ?></span><?php endif; ?>
    </div>

    <div class="form-group <?= error('status') ? 'has-error' : '' ?>">
        <label for="status">Estado</label>
        <select name="status" id="status">
            <option value="draft" <?= old('status', 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option>
            <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Activo</option>
            <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
        </select>
        <?php if (error('status')): ?><span class="field-error"><?= error('status') ?></span><?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear producto</button>
        <a href="<?= url('admin/products') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
