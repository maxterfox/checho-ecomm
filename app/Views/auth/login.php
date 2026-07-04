<div class="container">
    <div class="auth-form">
        <h1>Login</h1>
        <form action="<?= url('login') ?>" method="post">
            <?= csrfField() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="auth-link">Don't have an account? <a href="<?= url('register') ?>">Register</a></p>
    </div>
</div>
