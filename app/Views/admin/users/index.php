<div class="page-header">
    <h1>Usuarios</h1>
    <?php if (\App\Helpers\Permission::canModify((int) (\App\Core\Auth::user()['role_id'] ?? 0), 'users')): ?>
        <a href="<?= url('admin/users/create') ?>" class="btn btn-primary">Nuevo usuario</a>
    <?php endif; ?>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo electrónico</th>
            <th>Rol</th>
            <th>Acceso</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($users)): ?>
            <tr><td colspan="7" class="empty">No se encontraron usuarios.</td></tr>
        <?php else: ?>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= escape($u['name']) ?></td>
                    <td><?= escape($u['email']) ?></td>
                    <td><?= escape($u['role_name']) ?></td>
                    <td><?= $u['access_granted'] ? 'Sí' : 'No' ?></td>
                    <td><?= escape($u['status']) ?></td>
                    <td class="actions">
                        <a href="<?= url('admin/users/edit/' . $u['id']) ?>" class="btn btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
