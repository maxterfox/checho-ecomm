<div class="page-header">
    <h1>Edit User</h1>
    <a href="<?= url('admin/users') ?>" class="btn btn-secondary">Back</a>
</div>

<form action="<?= url('admin/users/' . $editUser['id']) ?>" method="post" class="form-card">
    <?= csrfField() ?>

    <?php $canEdit = fn($field) => !isset($fieldPerms[$field]) || $fieldPerms[$field]; ?>

    <div class="form-row">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?= escape($editUser['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= escape($editUser['email']) ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="password">New Password (leave blank to keep)</label>
            <input type="password" name="password" id="password" placeholder="Leave blank to keep current">
        </div>

        <div class="form-group <?= !$canEdit('role_id') ? 'disabled-field' : '' ?>">
            <label for="role_id">Role</label>
            <select name="role_id" id="role_id" <?= !$canEdit('role_id') ? 'disabled' : '' ?>>
                <option value="">Select role</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= $editUser['role_id'] == $r['id'] ? 'selected' : '' ?>>
                        <?= escape($r['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!$canEdit('role_id')): ?><span class="field-note">Only administrators can change role</span><?php endif; ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group <?= !$canEdit('access_granted') ? 'disabled-field' : '' ?>">
            <label for="access_granted">Access Granted</label>
            <select name="access_granted" id="access_granted" <?= !$canEdit('access_granted') ? 'disabled' : '' ?>>
                <option value="1" <?= $editUser['access_granted'] ? 'selected' : '' ?>>Yes</option>
                <option value="0" <?= !$editUser['access_granted'] ? 'selected' : '' ?>>No</option>
            </select>
            <?php if (!$canEdit('access_granted')): ?><span class="field-note">Only administrators can grant access</span><?php endif; ?>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active" <?= $editUser['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $editUser['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="suspended" <?= $editUser['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update User</button>
    </div>
</form>
