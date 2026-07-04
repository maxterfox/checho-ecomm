<div class="container">
    <div class="auth-box">
        <h1>Register</h1>

        <form action="<?= url('register') ?>" method="post">
            <?= csrfField() ?>

            <div class="form-group <?= error('name') ? 'has-error' : '' ?>">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name"
                       value="<?= escape(old('name')) ?>"
                       required>
                <?php if (error('name')): ?>
                    <span class="field-error"><?= escape(error('name')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= error('email') ? 'has-error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                       value="<?= escape(old('email')) ?>"
                       required autocomplete="email">
                <?php if (error('email')): ?>
                    <span class="field-error"><?= escape(error('email')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= error('password') ? 'has-error' : '' ?>">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                       minlength="6" required>
                <?php if (error('password')): ?>
                    <span class="field-error"><?= escape(error('password')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= error('password_confirm') ? 'has-error' : '' ?>">
                <label for="password_confirm">Confirm Password</label>
                <input type="password" name="password_confirm" id="password_confirm"
                       required>
                <?php if (error('password_confirm')): ?>
                    <span class="field-error"><?= escape(error('password_confirm')) ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>

        <p class="auth-link">Already have an account? <a href="<?= url('login') ?>">Login here</a>.</p>
    </div>
</div>
