<?php if (hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= escape(flash('success')) ?>
    </div>
<?php endif; ?>

<?php if (hasFlash('error')): ?>
    <div class="alert alert-error">
        <?= escape(flash('error')) ?>
    </div>
<?php endif; ?>
