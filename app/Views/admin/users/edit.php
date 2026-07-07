<div class="page-header">
    <h1>Editar usuario</h1>
    <a href="<?= url('admin/users') ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="<?= url('admin/users/' . $editUser['id']) ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <?php $canEdit = fn($field) => !isset($fieldPerms[$field]) || $fieldPerms[$field]; ?>

    <div class="form-row">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" value="<?= escape($editUser['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" value="<?= escape($editUser['email']) ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="password">Nueva contraseña (déjalo vacío para mantenerla)</label>
            <input type="password" name="password" id="password" placeholder="Déjalo vacío para mantener la actual">
        </div>

        <div class="form-group <?= !$canEdit('role_id') ? 'disabled-field' : '' ?>">
            <label for="role_id">Rol</label>
            <select name="role_id" id="role_id" <?= !$canEdit('role_id') ? 'disabled' : '' ?>>
                <option value="">Seleccionar rol</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= $editUser['role_id'] == $r['id'] ? 'selected' : '' ?>>
                        <?= escape($r['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!$canEdit('role_id')): ?><span class="field-note">Solo los administradores pueden cambiar el rol</span><?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group <?= !$canEdit('access_granted') ? 'disabled-field' : '' ?>">
            <label for="access_granted">Acceso concedido</label>
            <select name="access_granted" id="access_granted" <?= !$canEdit('access_granted') ? 'disabled' : '' ?>>
                <option value="1" <?= $editUser['access_granted'] ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= !$editUser['access_granted'] ? 'selected' : '' ?>>No</option>
            </select>
            <?php if (!$canEdit('access_granted')): ?><span class="field-note">Solo los administradores pueden conceder acceso</span><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="status">Estado</label>
            <select name="status" id="status">
                <option value="active" <?= $editUser['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
                <option value="inactive" <?= $editUser['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                <option value="suspended" <?= $editUser['status'] === 'suspended' ? 'selected' : '' ?>>Suspendido</option>
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar usuario</button>
    </div>
</form>
