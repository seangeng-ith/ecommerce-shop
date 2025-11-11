<?php

use App\Core\Helpers;

if (!function_exists('base_url')) {
    function base_url($p = '')
    {
        return Helpers::base_url($p);
    }
} ?>
<?php require_once 'partials/header.php'; ?>

<section class="register-section">
    <div class="register-wrapper">
        <div class="register-form-container">
            <h1 class="register-title">Register</h1>
            <div class="register-divider"></div>

            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Name" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="register-btn">Register</button>
            </form>

            <p class="login-link">Do you have an account? <a href="<?= base_url('index.php?page=login') ?>">Login</a></p>
        </div>
    </div>
</section>

<?php require_once 'partials/footer.php'; ?>