<?php

use App\Core\Helpers;

if (!function_exists('base_url')) {
  function base_url($p = '')
  {
    return Helpers::base_url($p);
  }
}
if (!function_exists('money')) {
  function money($n)
  {
    return Helpers::money((float)$n);
  }
} ?>
<?php require_once 'partials/header.php'; ?>

<section class="your-orders-section-full">
  <div class="orders-container">
    <h2 class="orders-main-title">Order details</h2>
    <div class="orders-main-divider"></div>
    <?php if (!$order): ?>
      <p>Order not found.</p>
    <?php else: ?>
      <div class="order-meta">
        <div class="order-meta-left">
          <span>Order #<?= (int)($order['id'] ?? 0) ?></span>
          <span class="status-badge status-<?= htmlspecialchars(str_replace(' ', '_', strtolower((string)($order['status'] ?? '')))) ?>"><?= htmlspecialchars((string)($order['status'] ?? '')) ?></span>
        </div>
        <div class="order-meta-right"><?= htmlspecialchars((string)($order['created_at'] ?? '')) ?></div>
      </div>
      <div class="orders-table-wrapper">
        <table class="orders-table">
          <thead>
            <tr class="orders-header">
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php $total = 0.0;
            foreach (($items ?? []) as $it): $sub = ((float)($it['price'] ?? 0)) * (int)($it['qty'] ?? 0);
              $total += $sub; ?>
              <tr class="order-row">
                <td class="product-cell">
                  <div class="product-info">
                    <img src="<?= base_url('img/' . (string)($it['image'] ?? '')) ?>" alt="<?= htmlspecialchars($it['name'] ?? '') ?>" class="product-img">
                    <span class="product-name"><?= htmlspecialchars($it['name'] ?? '') ?></span>
                  </div>
                </td>
                <td class="product-cell"><?= money($it['price'] ?? 0) ?></td>
                <td class="date-cell"><?= (int)($it['qty'] ?? 0) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($items ?? [])): ?>
              <tr class="order-row">
                <td colspan="3" class="product-cell">No items.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="checkout-actions" style="margin-top:20px;">
        <div class="checkout-total">Total amount: <strong><?= money($order['total_price'] ?? $total) ?></strong></div>
        <div>
          <a class="orders-btn" href="<?= base_url('index.php?page=account#your-orders') ?>" style="margin-right:8px;">Back to Orders</a>
          <a class="orders-btn" href="<?= base_url('index.php?page=payment&id=' . urlencode((string)($order['id'] ?? 0))) ?>">Pay Now</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>