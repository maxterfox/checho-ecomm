<div class="page-header">
    <h1>Nuevo usuario</h1>
    <a href="<?= url('admin/users') ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="<?= url('admin/users') ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <div class="form-row">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" value="<?= old('name') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" value="<?= old('email') ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-group">
            <label for="role_id">Rol</label>
            <select name="role_id" id="role_id">
                <option value="">Seleccionar rol</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= old('role_id') == $r['id'] ? 'selected' : '' ?>>
                        <?= escape($r['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="access_granted">Acceso concedido</label>
            <select name="access_granted" id="access_granted">
                <option value="1" <?= old('access_granted', '1') === '1' ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= old('access_granted') === '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Estado</label>
            <select name="status" id="status">
                <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Activo</option>
                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                <option value="suspended" <?= old('status') === 'suspended' ? 'selected' : '' ?>>Suspendido</option>
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear usuario</button>
    </div>
</form>
