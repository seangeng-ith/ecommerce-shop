<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } ?>

<?php $posts = $posts ?? [
  ['title'=>'Layering Essentials for Autumn','excerpt'=>'Build a versatile wardrobe with smart layers and textures.','category'=>'Guides','date'=>'Nov 2025'],
  ['title'=>'Trending Bags This Season','excerpt'=>'From mini crossbody to structured totes, see what’s hot.','category'=>'Trends','date'=>'Nov 2025'],
  ['title'=>'Comfort Meets Style: Sneakers','excerpt'=>'Why the right pair levels up both comfort and looks.','category'=>'Stories','date'=>'Nov 2025'],
]; ?>

<section class="blog-hero">
  <div class="container blog-hero-wrap">
    <p class="eyebrow">INSIGHTS</p>
    <h1>Stories & Tips</h1>
    <p class="sub">Explore trends, guides, and ideas from our editors</p>
    <a class="btn btn-dark" href="<?= base_url('index.php?page=shop') ?>">Shop Collections</a>
  </div>
  <div class="container chips">
    <span class="chip">All</span>
    <span class="chip">Guides</span>
    <span class="chip">Trends</span>
    <span class="chip">Stories</span>
  </div>
  </section>

<section class="container blog">
  <div class="blog-grid">
    <?php foreach($posts as $p): ?>
      <article class="card blog-card">
        <span class="badge-cat"><?= htmlspecialchars($p['category']) ?></span>
        <h3 class="title"><?= htmlspecialchars($p['title']) ?></h3>
        <p class="excerpt"><?= htmlspecialchars($p['excerpt']) ?></p>
        <div class="meta"><?= htmlspecialchars($p['date']) ?></div>
        <a class="btn btn-outline-secondary btn-sm" href="#">Read More</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>