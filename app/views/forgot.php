<?php

use App\Core\Helpers;

if (!function_exists('base_url')) {
    function base_url($p = '')
    {
        return Helpers::base_url($p);
    }
} ?>
 

<section class="login-section">
    <div class="login-wrapper">
        <div class="login-form-container">
            <h1 class="login-title">Reset your password</h1>
            <p class="login-subtitle">Enter your email to receive a reset link</p>
            <div class="login-divider"></div>

            <?php $sent = isset($sent) ? (bool)$sent : false; if ($sent): ?>
                <div class="alert alert-success">If the email exists, we sent a reset link.</div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Email" required>
                </div>

                <button type="submit" class="login-btn">Send reset link</button>
            </form>

            <p class="signup-link"><a href="<?= base_url('index.php?page=login') ?>">Back to login</a></p>
        </div>
    </div>
</section>