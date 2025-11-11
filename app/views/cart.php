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
<?php
$catalog = json_decode(file_get_contents(__DIR__ . '/../data/products.json'), true);
$items = cart_items($catalog);
$total = 0;
foreach ($items as $it) {
  $total += $it['subtotal'];
}
?>
<section class="container pad cart">
  <h2>Your Cart</h2>
  <?php if (!$items): ?>
    <p>Your cart is empty. <a class="btn" href="<?= base_url('index.php?page=shop') ?>">Go shopping</a></p>
  <?php else: ?>
    <form method="post">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td class="prod-cell">
                <img src="<?= base_url('img/' . htmlspecialchars($it['image'], ENT_QUOTES)) ?>" alt="">
                <div><strong><?= htmlspecialchars($it['name']) ?></strong><br><span class="muted"><?= money($it['price']) ?></span><br>
                  <a class="remove" href="<?= base_url('index.php?page=cart&remove=' . urlencode($it['id'])) ?>">Remove</a>
                </div>
              </td>
              <td class="qty-cell">
                <input type="number" min="0" name="qty[<?= htmlspecialchars($it['id']) ?>]" value="<?= (int)$it['qty'] ?>">
                <span class="edit-hint">Edit</span>
              </td>
              <td class="money"><?= money($it['subtotal']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td></td>
            <td class="money label">Subtotal</td>
            <td class="money"><?= money($total) ?></td>
          </tr>
        </tfoot>
      </table>
      <div class="cart-actions">
        <button class="btn" name="update_cart" value="1">Update Cart</button>
        <a class="btn btn-dark" href="#">Checkout</a>
      </div>
    </form>
  <?php endif; ?>
</section>