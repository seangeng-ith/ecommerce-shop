<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } if(!function_exists('money')){ function money($n){ return Helpers::money((float)$n);} } ?>
<?php
$items = $items ?? [];
$page  = $page ?? 1;
$pages = $pages ?? 1;
$cat   = $cat ?? 'all';
$sort  = $sort ?? 'popular';
$qs = function($override=[]) {
  $base = array_merge($_GET, $override);
  return '?' . http_build_query($base);
};
?>
<section class="container pad">
  <h2>Shop</h2>
  <div class="shop-top">
    <p class="muted">Showing <?= (int)count($items) ?> results</p>
    <div class="filter-pills">
      <span class="chip"><?= htmlspecialchars(ucfirst($cat)) ?></span>
      <?php $min = trim((string)($_GET['min'] ?? '')); $max = trim((string)($_GET['max'] ?? '')); ?>
      <?php if ($min !== ''): ?><span class="chip">Min $<?= htmlspecialchars($min) ?></span><?php endif; ?>
      <?php if ($max !== ''): ?><span class="chip">Max $<?= htmlspecialchars($max) ?></span><?php endif; ?>
    </div>
  </div>

  <form class="filters" method="get">
    <input type="hidden" name="page" value="shop">
    <input type="hidden" name="p" value="1">
    <select name="cat">
      <?php $cats=['all'=>'All','clothes'=>'Clothes','bags'=>'Bags','shoes'=>'Shoes'];
      foreach($cats as $k=>$label): ?>
        <option value="<?= $k ?>" <?= ($cat===$k?'selected':'') ?>><?= $label ?></option>
      <?php endforeach; ?>
    </select>
    <select name="sort">
      <option value="popular" <?= $sort==='popular'?'selected':'' ?>>Popular</option>
      <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Price: Low to High</option>
      <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Price: High to Low</option>
      <option value="rating" <?= $sort==='rating'?'selected':'' ?>>Top Rated</option>
    </select>
    <input type="number" step="0.01" name="min" placeholder="Min $" value="<?= htmlspecialchars($_GET['min'] ?? '') ?>">
    <input type="number" step="0.01" name="max" placeholder="Max $" value="<?= htmlspecialchars($_GET['max'] ?? '') ?>">
    <button class="btn btn-dark">Apply</button>
  </form>

  <div class="shop-banner">
    <div>Autumn Sale · Extra 20% off selected collections</div>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('index.php?page=shop') ?>">Browse</a>
  </div>

  <div class="row g-3">
    <?php foreach($items as $p): ?>
      <div class="col-6 col-md-3 d-flex">
        <div class="card product-card h-100 w-100">
          <a href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">
            <img class="card-img-top" loading="lazy" width="600" height="600" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt="">
          </a>
          <div class="card-body">
            <div class="stars"><?= str_repeat('★',$p['rating']) ?><span class="muted"><?= str_repeat('☆',5-$p['rating']) ?></span></div>
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

  <nav class="pagination">
    <?php for($i=1;$i<=$pages;$i++): ?>
      <a class="<?= $i===$page?'active':'' ?>" href="<?= $qs(['p'=>$i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
  </nav>
</section>
