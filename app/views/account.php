
<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } ?>
<?php require_once 'partials/header.php'; ?>

<section class="account-section">
  <div class="account-wrapper">
    <div class="account-card">
      <div class="account-container">
        <div class="account-tab account-info-tab active">
          <div class="account-info-container">
            <h2 class="tab-title">Account Info</h2>
            <div class="account-divider"></div>
            <div class="account-details">
              <div class="detail-item">
                <p class="detail-label">Name</p>
                <p class="detail-value"><?= htmlspecialchars(($user['name'] ?? 'Guest')) ?></p>
              </div>
              <div class="detail-item">
                <p class="detail-label">Email</p>
                <p class="detail-value"><?= htmlspecialchars(($user['email'] ?? '')) ?></p>
              </div>
              <div class="account-links">
                <a href="<?= base_url('index.php?page=orders') ?>" class="account-link">Your orders</a>
                <a href="<?= base_url('index.php?page=logout') ?>" class="account-link logout-link">Logout</a>
              </div>
            </div>
          </div>
        </div>
        <div class="change-password-container">
          <h2 class="tab-title">Change Password</h2>
          <div class="account-divider"></div>
          <?php if (isset($pw_success) && $pw_success): ?>
            <div class="alert alert-success">Password updated successfully.</div>
          <?php endif; ?>
          <?php if (isset($pw_error) && $pw_error !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($pw_error) ?></div>
          <?php endif; ?>
          <form method="POST" class="password-form">
            <div class="form-group">
              <label for="current_password" class="form-label">Current Password</label>
              <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="new_password" class="form-label">New Password</label>
              <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password" class="form-label">Confirm New Password</label>
              <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="change-password-btn">Change Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Your Orders Section Full Width -->
<section class="your-orders-section-full" id="your-orders">
  <div class="orders-container">
    <h2 class="orders-main-title">Your Orders</h2>
    <div class="orders-main-divider"></div>
    <div class="orders-table-wrapper">
      <table class="orders-table">
        <thead>
          <tr class="orders-header">
            <th>Order id</th>
            <th>Order cost</th>
            <th>Order status</th>
            <th>Order Date</th>
            <th>Order details</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach(($orders ?? []) as $o): ?>
            <tr class="order-row">
              <td class="product-cell"><?= (int)$o['id'] ?></td>
              <td class="product-cell">$<?= number_format((float)($o['total_price'] ?? 0), 2) ?></td>
              <td class="product-cell"><span class="status-badge status-<?= htmlspecialchars(str_replace(' ','_', strtolower((string)($o['status'] ?? '')))) ?>"><?= htmlspecialchars((string)($o['status'] ?? '')) ?></span></td>
              <td class="date-cell"><?= htmlspecialchars((string)($o['created_at'] ?? '')) ?></td>
              <td class="date-cell"><a class="orders-btn" href="<?= base_url('index.php?page=order&id=' . urlencode((string)$o['id'])) ?>">details</a></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($orders ?? [])): ?>
            <tr class="order-row"><td colspan="5" class="product-cell">No orders yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

