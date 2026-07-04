<div class="container">
    <div class="auth-box">
        <h1>Login</h1>

        <form action="<?= url('login') ?>" method="post">
            <?= csrfField() ?>

            <div class="form-group <?= error('email') ? 'has-error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                       value="<?= escape(old('email')) ?>"
                       autocomplete="email">
                <?php if (error('email')): ?>
                    <span class="field-error"><?= escape(error('email')) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?= error('password') ? 'has-error' : '' ?>">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <?php if (error('password')): ?>
                    <span class="field-error"><?= escape(error('password')) ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <p class="auth-link">No account? <a href="<?= url('register') ?>">Register here</a>.</p>
    </div>
</div>
