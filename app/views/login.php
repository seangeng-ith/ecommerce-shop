<?php

use App\Core\Helpers;

if (!function_exists('base_url')) {
    function base_url($p = '')
    {
        return Helpers::base_url($p);
    }
} ?>
<?php require_once 'partials/header.php'; ?>

<section class="login-section">
    <div class="login-wrapper">
        <div class="login-form-container">
            <h1 class="login-title">Login</h1>
            <div class="login-divider"></div>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <p class="signup-link">Don't have account? <a href="<?= base_url('index.php?page=register') ?>">Register</a></p>
        </div>
    </div>
</section>

<?php require_once 'partials/footer.php'; ?>