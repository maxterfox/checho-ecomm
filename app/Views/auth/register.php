<div class="container">
    <div class="auth-form">
        <h1>Create Account</h1>
        <form action="<?= url('register') ?>" method="post">
            <?= csrfField() ?>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <p class="auth-link">Already have an account? <a href="<?= url('login') ?>">Login</a></p>
    </div>
</div>
