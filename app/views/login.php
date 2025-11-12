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
            <h1 class="login-title">Welcome back</h1>
            <p class="login-subtitle">Sign in to continue</p>
            <div class="login-divider"></div>

            <form method="POST" class="login-form">
                <?php $redir = isset($_GET['redirect']) ? (string)$_GET['redirect'] : ''; if ($redir !== ''): ?>
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redir, ENT_QUOTES) ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Password" required>
                </div>

                <div class="form-row">
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="forgot-link"><a href="<?= base_url('index.php?page=forgot') ?>">Forgot password?</a></div>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <p class="signup-link">Don't have account? <a href="<?= base_url('index.php?page=register') ?>">Register</a></p>
        </div>
    </div>
</section>