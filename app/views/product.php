<?php
$all = json_decode(file_get_contents(__DIR__ . '/../data/products.json'), true);
$id = $_GET['id'] ?? '';
$product = null;
foreach($all as $p){ if ((string)$p['id']===(string)$id){ $product=$p; break; } }
if (!$product){ echo "<section class='container pad'><p>Product not found.</p></section>"; return; }

$gallery = $product['gallery'] ?? [$product['image']];
?>
<section class="container pad product">
  <div class="prod-grid">
    <div class="prod-media">
      <img class="hero" src="<?= base_url('img/' . htmlspecialchars($gallery[0],ENT_QUOTES)) ?>" alt="">
      <div class="thumbs">
        <?php foreach($gallery as $img): ?>
          <img src="<?= base_url('img/' . htmlspecialchars($img,ENT_QUOTES)) ?>" alt="" onclick="document.querySelector('.prod-media .hero').src=this.src">
        <?php endforeach; ?>
      </div>
    </div>
    <div class="prod-info">
      <p class="crumbs"><?= htmlspecialchars(ucfirst($product['category'])) ?></p>
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <div class="big-price"><?= money($product['price']) ?></div>
      <form method="post" class="buy-row">
        <input type="number" min="1" name="qty" value="1">
        <button class="btn btn-dark" name="add_to_cart" value="<?= htmlspecialchars($product['id']) ?>">ADD TO CART</button>
        <input type="hidden" name="redirect" value="<?= base_url('index.php?page=cart') ?>">
      </form>
      <div class="details">
        <h3>Product details</h3>
        <p><?= htmlspecialchars($product['description'] ?? 'The details of this product will be displayed shortly.') ?></p>
      </div>
    </div>
  </div>
</section>
