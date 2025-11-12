<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } if(!function_exists('money')){ function money($n){ return Helpers::money((float)$n);} } ?>
<?php require_once 'partials/header.php'; ?>

<section class="your-orders-section-full">
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
              <td class="product-cell"><?= money($o['total_price'] ?? 0) ?></td>
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
