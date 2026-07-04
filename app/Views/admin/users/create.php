<div class="container">
    <div class="page-header">
        <h1>Create User</h1>
    </div>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger"><?= flash('error') ?></div>
    <?php endif; ?>

    <form action="<?= url('/admin/users/store') ?>" method="POST">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= escape(old('name')) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= escape(old('email')) ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="role_id">Role</label>
            <select name="role_id" id="role_id" class="form-control">
                <option value="">-- Select Role --</option>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= escape($role['id']) ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>>
                            <?= escape($role['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="access_granted" value="1" <?= old('access_granted') ? 'checked' : '' ?>>
                Access Granted
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= url('/admin/users') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
