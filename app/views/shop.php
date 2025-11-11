<?php
$all = json_decode(file_get_contents(__DIR__ . '/../data/products.json'), true);

// filter
$cat = $_GET['cat'] ?? 'all';
$allowedCats = ['all','clothes','bags','shoes'];
if (!in_array($cat, $allowedCats, true)) { $cat = 'all'; }
if ($cat !== 'all') $all = array_values(array_filter($all, fn($p)=>$p['category']===$cat));

$min = (isset($_GET['min']) && $_GET['min'] !== '') ? (float)$_GET['min'] : null;
$max = (isset($_GET['max']) && $_GET['max'] !== '') ? (float)$_GET['max'] : null;
if ($min !== null) $all = array_values(array_filter($all, fn($p)=>$p['price'] >= $min));
if ($max !== null) $all = array_values(array_filter($all, fn($p)=>$p['price'] <= $max));

// sort
$sort = $_GET['sort'] ?? 'popular';
usort($all, function($a,$b) use($sort){
  return match($sort){
    'price_asc' => $a['price'] <=> $b['price'],
    'price_desc' => $b['price'] <=> $a['price'],
    'rating' => $b['rating'] <=> $a['rating'],
    default => $b['rating'] <=> $a['rating'],
  };
});

// pagination
$page = max(1, (int)($_GET['p'] ?? 1));
$per = 8;
$total = count($all);
$pages = max(1, (int)ceil($total / $per));
$offset = ($page-1)*$per;
$items = array_slice($all, $offset, $per);
$qs = function($override=[]) {
  $base = array_merge($_GET, $override);
  return '?' . http_build_query($base);
};
?>
<section class="container pad">
  <h2>Shop</h2>

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
    <button class="btn">Apply</button>
  </form>

  <div class="products">
    <?php foreach($items as $p): ?>
      <article class="card">
        <a class="media tilt" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>"><img loading="lazy" src="<?= base_url('img/' . htmlspecialchars($p['image'],ENT_QUOTES)) ?>" alt=""></a>
        <div class="overlay">
          <div class="actions">
            <a class="btn-ghost ripple" href="<?= base_url('index.php?page=product&id=' . urlencode($p['id'])) ?>">View</a>
            <form method="post">
              <button class="btn-soft ripple" name="add_to_cart" value="<?= $p['id'] ?>">Add to Cart</button>
            </form>
          </div>
        </div>
        <div class="info">
          <div class="stars"><?= str_repeat('★',$p['rating']) ?><span class="muted"><?= str_repeat('☆',5-$p['rating']) ?></span></div>
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <div class="price">$<?= number_format($p['price'],2) ?></div>
          <form method="post"><button class="btn" name="add_to_cart" value="<?= $p['id'] ?>">BUY NOW</button></form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <nav class="pagination">
    <?php for($i=1;$i<=$pages;$i++): ?>
      <a class="<?= $i===$page?'active':'' ?>" href="<?= $qs(['p'=>$i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
  </nav>
</section>
