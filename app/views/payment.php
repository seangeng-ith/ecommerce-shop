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
    <h2 class="orders-main-title">Payment</h2>
    <div class="orders-main-divider"></div>
    <?php if (!$order): ?>
      <p>Order not found.</p>
    <?php else: ?>
      <div class="order-meta centered">
        <div class="order-meta-left">
          <span>Order #<?= (int)($order['id'] ?? 0) ?></span>
          <span class="status-badge status-<?= htmlspecialchars(str_replace(' ', '_', strtolower((string)($order['status'] ?? '')))) ?>"><?= htmlspecialchars((string)($order['status'] ?? '')) ?></span>
        </div>
      </div>
      <div class="payment-total">Total payment: <?= money($order['total_price'] ?? 0) ?></div>
      <div style="margin-top:20px;" class="paypal-wrap">
        <div id="paypal-button-container"></div>
      </div>
      <div class="checkout-actions" style="margin-top:20px;">
        <a class="orders-btn" href="<?= base_url('index.php?page=order&id=' . urlencode((string)($order['id'] ?? 0))) ?>" style="margin-right:8px;">Back to details</a>
        <a class="orders-btn" href="<?= base_url('index.php?page=account#your-orders') ?>">Back to Orders</a>
      </div>
      <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
      <script>
      (function(){
        var amount = '<?= number_format((float)($order['total_price'] ?? 0), 2, '.', '') ?>';
        var postUrl = '<?= base_url('index.php?page=payment&id=' . urlencode((string)($order['id'] ?? 0))) ?>';
        paypal.Buttons({
          createOrder: function(data, actions) {
            return actions.order.create({ purchase_units: [{ amount: { value: amount } }] });
          },
          onApprove: function(data, actions) {
            return actions.order.capture().then(function(details){
              var txn = String((details && details.id) || (data && data.orderID) || '');
              var form = new FormData();
              form.set('capture','1');
              form.set('transaction_id', txn);
              fetch(postUrl, { method:'POST', body: form }).then(function(){ window.location.assign('<?= base_url('index.php?page=orders') ?>'); });
            });
          }
        }).render('#paypal-button-container');
      })();
      </script>
    <?php endif; ?>
  </div>
</section>