<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } if(!function_exists('money')){ function money($n){ return Helpers::money((float)$n);} } ?>
<?php if(!$product){ echo "<section class='container pad'><p>Product not found.</p></section>"; return; } $gallery=$product['gallery']??[$product['image']]; ?>
<section class="container pad product">
  <div class="prod-grid">
    <div class="prod-media">
      <img class="hero" src="<?= base_url('img/' . htmlspecialchars($gallery[0],ENT_QUOTES)) ?>" alt="">
    </div>
    <div class="prod-info">
      <span class="badge-cat"><?= htmlspecialchars(ucfirst($product['category'])) ?></span>
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <div class="rating">★★★★★</div>
      <div class="big-price"><?= money($product['price']) ?></div>
      <ul class="meta-list">
        <li>Free shipping over $50</li>
        <li>30-day returns</li>
      </ul>
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

<?php $related = $related ?? []; if ($related): ?>
<section class="container related">
  <h3>Related products</h3>
  <div class="row g-3">
    <?php foreach($related as $p): ?>
      <div class="col-6 col-md-3 d-flex">
        <div class="card product-card h-100 w-100">
          <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">
            <img class="card-img-top" loading="lazy" width="600" height="600" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>">
          </a>
          <div class="card-body">
            <div class="stars">★★★★★</div>
            <h5 class="card-title text-truncate"><?= htmlspecialchars($p['name']) ?></h5>
            <p class="card-text mb-2">$<?= number_format($p['price'],2) ?></p>
            <div class="actions d-flex gap-2">
              <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
              <form method="post">
                <button class="btn btn-primary btn-sm" name="add_to_cart" value="<?= $p['id'] ?>">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
