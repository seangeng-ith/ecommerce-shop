<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } if(!function_exists('money')){ function money($n){ return Helpers::money((float)$n);} } ?>
<?php $feat = $feat ?? []; $clothes = $clothes ?? []; $bags = $bags ?? []; $shoes = $shoes ?? []; ?>
<section class="hero">
  <div class="container hero-wrap">
    <div class="hero-copy">
      <p class="eyebrow">NEW ARRIVALS</p>
      <h1><span>Best Prices</span> This Season</h1>
      <p class="sub">Eshop offers the best products for the most affordable prices</p>
      <a class="btn btn-dark" href="<?= base_url('index.php?page=shop') ?>">Shop Now</a>
      <div class="hero-highlights">
        <span class="pill">Free Shipping</span>
        <span class="pill">30-day Returns</span>
        <span class="pill">Secure Checkout</span>
      </div>
    </div>
    <div class="hero-media">
      <div class="art">
   
        <div class="bubble b1"></div>
        <div class="bubble b2"></div>
        <div class="bubble b3"></div>
      </div>
    </div>
  </div>
</section>

<section class="brands">
  <div class="container brands-row">
    <img loading="lazy" src="<?= base_url('img/nike.svg') ?>" alt="Nike">
    <img loading="lazy" src="<?= base_url('img/newyorker.svg') ?>" alt="New Yorker">
    <img loading="lazy" src="<?= base_url('img/mothercare.svg') ?>" alt="Mothercare">
    <img loading="lazy" src="<?= base_url('img/coach.svg') ?>" alt="Coach">
  </div>
</section>

<section class="container featured">
  <h2>Featured</h2>
  <div class="row g-3">
    <?php foreach($feat as $p): ?>
      <div class="col-6 col-md-3 d-flex">
        <div class="card product-card h- w-100">
          <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">
            <img class="card-img-top" loading="lazy" width="600" height="600" src="<?= base_url('img/' . htmlspecialchars(($p['image'] ?? $p['image_url'] ?? 'placeholder.png'),ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>">
          </a>
          <div class="card-body">
            <div class="stars">★★★★★</div>
            <h5 class="card-title text-truncate"><?= htmlspecialchars($p['name']) ?></h5>
            <p class="card-text mb-2">$<?= number_format($p['price'], 2) ?></p>
            <div class="actions d-flex gap-2">
              <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
              <form method="post">
                <input type="hidden" name="redirect" value="<?= base_url('index.php?page=cart') ?>">
                <button class="btn btn-primary btn-sm" name="add_to_cart" value="<?= $p['id'] ?>">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="mid-banner">
  <div class="container mid-wrap">
    <div class="mid-copy">
      <p class="eyebrow">MID SEASON'S SALE</p>
      <h2>Autumn Collection<br><span class="accent">UP to 30% OFF</span></h2>
      <a class="btn btn-dark" href="<?= base_url('index.php?page=shop') ?>">Shop Now</a>
    </div>
  </div>
</section>

<section class="promo-banner">
  <div class="container promo-wrap">
    <div class="promo-copy">
      <p class="eyebrow">Limited Time</p>
      <h2>Holiday Mega Sale</h2>
      <p class="sub">Extra 20% off selected collections</p>
      <a class="btn btn-dark" href="<?= base_url('index.php?page=shop') ?>">Shop Deals</a>
    </div>
  </div>
  </section>

<section class="container color-panels">
  <div class="panel-grid">
    <div class="panel panel-violet">
      <div class="panel-title">Free Shipping</div>
      <p>On orders over $50</p>
      <a class="panel-link" href="<?= base_url('index.php?page=shop') ?>">Explore</a>
    </div>
    <div class="panel panel-cyan">
      <div class="panel-title">New Arrivals</div>
      <p>Fresh styles weekly</p>
      <a class="panel-link" href="<?= base_url('index.php?page=shop') ?>">Browse</a>
    </div>
    <div class="panel panel-green">
      <div class="panel-title">Secure Payments</div>
      <p>Pay safely with PayPal</p>
      <a class="panel-link" href="<?= base_url('index.php?page=shop') ?>">Learn more</a>
    </div>
    <div class="panel panel-amber">
      <div class="panel-title">Member Rewards</div>
      <p>Earn points every purchase</p>
      <a class="panel-link" href="<?= base_url('index.php?page=register') ?>">Join now</a>
    </div>
  </div>
  </section>

<section class="container category">
  <h2>Clothes</h2>
  <div class="row g-3"><?php foreach($clothes as $p): ?>
    <div class="col-6 col-md-3 d-flex">
      <div class="card product-card h-100 w-100">
        <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">
          <img class="card-img-top" loading="lazy" width="600" height="600" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>">
        </a>
        <div class="card-body">
          <div class="stars">★★★★★</div>
          <h5 class="card-title text-truncate"><?= htmlspecialchars($p['name']) ?></h5>
          <div class="fw-bold mb-2">$<?= number_format($p['price'],2) ?></div>
          <div class="actions d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
          </div>
        </div>
      </div>
    </div><?php endforeach; ?>
  </div>
</section>

<section class="container category">
  <h2>Bags</h2>
  <div class="row g-3"><?php foreach($bags as $p): ?>
    <div class="col-6 col-md-3 d-flex">
      <div class="card product-card h-100 w-100">
        <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">
          <img class="card-img-top" loading="lazy" width="600" height="600" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>">
        </a>
        <div class="card-body">
          <div class="stars">★★★★★</div>
          <h5 class="card-title text-truncate"><?= htmlspecialchars($p['name']) ?></h5>
          <div class="fw-bold mb-2">$<?= number_format($p['price'],2) ?></div>
          <div class="actions d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
          </div>
        </div>
      </div>
    </div><?php endforeach; ?>
  </div>
</section>

<section class="container category">
  <h2>Shoes</h2>
  <div class="row g-3"><?php foreach($shoes as $p): ?>
    <div class="col-6 col-md-3 d-flex">
      <div class="card product-card h-100 w-100">
        <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">
          <img class="card-img-top" loading="lazy" width="600" height="600" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>">
        </a>
        <div class="card-body">
          <div class="stars">★★★★★</div>
          <h5 class="card-title text-truncate"><?= htmlspecialchars($p['name']) ?></h5>
          <div class="fw-bold mb-2">$<?= number_format($p['price'],2) ?></div>
          <div class="actions d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
          </div>
        </div>
      </div>
    </div><?php endforeach; ?>
  </div>
</section>
