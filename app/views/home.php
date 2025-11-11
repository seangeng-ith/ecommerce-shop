<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } if(!function_exists('money')){ function money($n){ return Helpers::money((float)$n);} } ?>
<?php // home ?>
<section class="hero">
  <div class="container hero-wrap">
    <div class="hero-copy">
      <p class="eyebrow">NEW ARRIVALS</p>
      <h1><span>Best Prices</span> This Season</h1>
      <p class="sub">Eshop offers the best products for the most affordable prices</p>
      <a class="btn btn-dark" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">Shop Now</a>
    </div>
    <div class="hero-media">
      <img src="<?= base_url('img/hero.png') ?>" alt="Featured" />
    </div>
  </div>
</section>

<section class="brands">
  <div class="container brands-row">
    <img src="<?= base_url('img/nike.svg') ?>" alt="Nike">
    <img src="<?= base_url('img/newyorker.svg') ?>" alt="New Yorker">
    <img src="<?= base_url('img/mothercare.svg') ?>" alt="Mothercare">
    <img src="<?= base_url('img/coach.svg') ?>" alt="Coach">
  </div>
</section>

<section class="container featured">
  <h2>Featured</h2>
  <div class="products">
    <?php
      $data = json_decode(file_get_contents(__DIR__ . '/../data/products.json'), true);
      $feat = array_slice($data, 0, 4);
      foreach($feat as $p): ?>
      <article class="card reveal">
        <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>" class="media tilt">
          <img loading="lazy" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt="<?= htmlspecialchars($p['name'],ENT_QUOTES) ?>">
        </a>
        <div class="overlay">
          <div class="actions">
            <a class="btn-ghost ripple" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
            <form method="post">
              <input type="hidden" name="redirect" value="<?= base_url('index.php?page=cart') ?>">
              <button class="btn-soft ripple" name="add_to_cart" value="<?= $p['id'] ?>">Add to Cart</button>
            </form>
          </div>
        </div>
        <div class="info">
          <div class="stars"><?= str_repeat('★', $p['rating']) ?><span class="muted"><?= str_repeat('☆', 5-$p['rating']) ?></span></div>
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <div class="price">$<?= number_format($p['price'], 2) ?></div>
          <form method="post"><button class="btn" name="add_to_cart" value="<?= $p['id'] ?>">BUY NOW</button></form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="mid-banner">
  <div class="container mid-wrap">
    <div class="mid-copy">
      <p class="eyebrow">MID SEASON'S SALE</p>
      <h2>Autumn Collection<br><span class="accent">UP to 30% OFF</span></h2>
      <a class="btn btn-dark" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">Shop Now</a>
    </div>
  </div>
</section>

<section class="container category">
  <h2>Clothes</h2>
  <div class="products"><?php for($i=1;$i<=4;$i++): ?>
    <article class="card mini">
      <a class="media"><img src="<?= base_url('img/clothes-' . $i . '.svg') ?>" alt=""></a>
      <div class="info"><div class="stars">★★★★★</div><h3>Jacket <?= $i ?></h3><div class="price">$<?= 49+$i ?></div></div>
    </article><?php endfor; ?>
  </div>
</section>

<section class="container category">
  <h2>Bags</h2>
  <div class="products"><?php for($i=1;$i<=4;$i++): ?>
    <article class="card mini">
      <a class="media"><img src="<?= base_url('img/bags-' . $i . '.svg') ?>" alt=""></a>
      <div class="info"><div class="stars">★★★★★</div><h3>Bag <?= $i ?></h3><div class="price">$<?= 39+$i ?></div></div>
    </article><?php endfor; ?>
  </div>
</section>

<section class="container category">
  <h2>Shoes</h2>
  <div class="products"><?php for($i=1;$i<=4;$i++): ?>
    <article class="card mini">
      <a class="media"><img src="<?= base_url('img/shoes-' . $i . '.svg') ?>" alt=""></a>
      <div class="info"><div class="stars">★★★★★</div><h3>Shoes <?= $i ?></h3><div class="price">$<?= 59+$i ?></div></div>
    </article><?php endfor; ?>
  </div>
</section>
