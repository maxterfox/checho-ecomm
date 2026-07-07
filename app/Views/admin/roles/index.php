<div class="container">
    <div class="page-header">
        <h1>Roles</h1>
        <a href="<?= url('admin/roles/create') ?>" class="btn btn-primary">Añadir rol</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Módulos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roles)): ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= escape($role['name']) ?></td>
                        <td><?= (int) $role['module_count'] ?> módulo(s)</td>
                        <td>
                            <a href="<?= url('admin/roles/edit/' . $role['id']) ?>" class="btn btn-secondary btn-sm">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No se encontraron roles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= url('admin') ?>" class="btn btn-secondary">Volver al panel</a>
</div>
