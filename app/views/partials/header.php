<?php
$conf = require __DIR__ . '/../../../config/config.php';
$appName = htmlspecialchars($conf['app_name'] ?? 'Eshop', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $appName ?></title>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
  <?php $page = $_GET['page'] ?? 'home'; ?>
  <?php if ($page === 'login'): ?>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
  <?php elseif ($page === 'register'): ?>
    <link rel="stylesheet" href="<?= base_url('css/register.css') ?>">
  <?php elseif ($page === 'forgot'): ?>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
  <?php elseif ($page === 'account' || $page === 'orders' || $page === 'order' || $page === 'payment'): ?>
    <link rel="stylesheet" href="<?= base_url('css/account.css') ?>">
  <?php elseif ($page === 'checkout'): ?>
    <link rel="stylesheet" href="<?= base_url('css/checkout.css') ?>">
  <?php elseif ($page === 'contact'): ?>
    <link rel="stylesheet" href="<?= base_url('css/contact.css') ?>">
  <?php endif; ?>
</head>

<body>
  <header class="topbar">
    <div class="container nav">
      <a class="brand" href="<?= base_url('index.php') ?>"><span><?= $appName ?></span></a>
      <nav>
        <a href="<?= base_url('index.php?page=home') ?>" class="<?= ($_GET['page'] ?? 'home') === 'home' ? 'active' : '' ?>">Home</a>
        <a href="<?= base_url('index.php?page=shop') ?>" class="<?= ($_GET['page'] ?? '') === 'shop' ? 'active' : '' ?>">Shop</a>
        <a href="<?= base_url('index.php?page=blog') ?>" class="<?= ($_GET['page'] ?? '') === 'blog' ? 'active' : '' ?>">Blog</a>
        <a href="<?= base_url('index.php?page=contact') ?>" class="<?= ($_GET['page'] ?? '') === 'contact' ? 'active' : '' ?>">Contact</a>
      </nav>
      <div class="icons">
        <a class="cart" href="<?= base_url('index.php?page=cart') ?>">🛒 <span id="cart-count"><?= \App\Models\CartModel::count() ?></span></a>
        <?php $u = $_SESSION['user'] ?? null; if ($u): ?>
          <a class="user-icon" href="<?= base_url('index.php?page=account') ?>" title="account">👤</a>
          <a class="logout" href="<?= base_url('index.php?page=logout') ?>">Logout</a>
        <?php else: ?>
          <a class="login" href="<?= base_url('index.php?page=login') ?>">Login</a>
          <a class="register" href="<?= base_url('index.php?page=register') ?>">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <script>
  (function(){
    var CURRENT_PAGE = '<?= $page ?>';
    function updateCount(n){
      var el=document.querySelector('.icons .cart');
      if(!el) return;
      var span=el.querySelector('#cart-count');
      if(span){ span.textContent=String(n); } else { el.textContent='🛒 '+String(n); }
    }
    function updateCartTotalDisplay(totalFmt){
      var cell=document.querySelector('.cart-table tfoot td.money:last-child');
      if(cell){ cell.textContent= totalFmt || ('$'+Number(total||0).toFixed(2)); }
    }
    function handleAddToCart(e){
      var btn=e.target.closest('button[name="add_to_cart"]');
      if(!btn) return;
      var form=btn.form||btn.closest('form');
      if(!form) return;
      e.preventDefault();
      var id=btn.value;
      var qtyInput=form.querySelector('input[name="qty"]');
      var qty=qtyInput? qtyInput.value : '1';
      var data=new URLSearchParams();
      data.set('add_to_cart', id);
      data.set('qty', qty);
      fetch('<?= base_url('index.php?page=cart') ?>', { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' }, body:data })
        .then(function(r){ return r.json(); })
        .then(function(j){
          if(j && j.require_login){ window.location.assign(j.login_url); return; }
          if(j && j.ok){ updateCount(j.count); btn.disabled=true; setTimeout(function(){ btn.disabled=false; }, 400);} 
        });
    }
    function handleRemoveFromCart(e){
      var a=e.target.closest('a.remove');
      if(!a) return;
      e.preventDefault();
      fetch(a.href, { method:'GET', headers:{ 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' } })
        .then(function(r){ return r.json(); })
        .then(function(j){
          if(j && j.ok){
            updateCount(j.count);
            var row=a.closest('tr'); if(row){ row.remove(); }
            updateCartTotalDisplay(j.total_fmt);
          }
        });
    }
    document.addEventListener('click', function(e){
      if(e.target && e.target.closest('button[name="add_to_cart"]')){ handleAddToCart(e); }
      else if(e.target && e.target.closest('a.remove')){ handleRemoveFromCart(e); }
      else if(CURRENT_PAGE==='shop' && e.target && e.target.closest('nav.pagination a')){
        var a=e.target.closest('a');
        if(!a) return;
        e.preventDefault();
        var url=new URL(a.href, window.location.origin);
        url.searchParams.set('ajax','1');
        fetch(url.toString(), { headers:{ 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' } })
          .then(function(r){ return r.json(); })
          .then(function(j){ if(j && j.ok){ renderShop(j); } });
      }
    });
    function renderShop(j){
      var grid=document.querySelector('.container.pad .row.g-3');
      if(!grid) return;
      var html='';
      (j.items||[]).forEach(function(p){
        html += '\n      <div class="col-6 col-md-3 d-flex">\n        <div class="card product-card h-100 w-100">\n          <a href="'+p.product_url+'">\n            <img class="card-img-top" loading="lazy" width="600" height="600" src="'+p.image_url+'" alt="">\n          </a>\n          <div class="card-body">\n            <div class="stars">'+('★'.repeat(p.rating))+'<span class="muted">'+('☆'.repeat(5-p.rating))+'</span></div>\n            <h5 class="card-title text-truncate">'+escapeHtml(p.name)+'</h5>\n            <p class="card-text mb-2">$'+Number(p.price||0).toFixed(2)+'</p>\n            <div class="actions d-flex gap-2">\n              <a class="btn btn-outline-secondary btn-sm" href="'+p.product_url+'">View</a>\n              <form method="post">\n                <button class="btn btn-primary btn-sm" name="add_to_cart" value="'+p.id+'">Add to Cart</button>\n              </form>\n            </div>\n          </div>\n        </div>\n      </div>';
      });
      grid.innerHTML = html || '<p>No products found.</p>';
      var pag=document.querySelector('.container.pad nav.pagination');
      if(pag){
        var u=new URL(window.location.href);
        var qs=function(i){ u.searchParams.set('p', String(i)); return u.toString(); };
        var ph='';
        var pages=Number(j.pages||1), page=Number(j.page||1);
        for(var i=1;i<=pages;i++){ ph += '<a class="'+(i===page?'active':'')+'" href="'+qs(i)+'">'+i+'</a>'; }
        pag.innerHTML = ph;
      }
    }
    function escapeHtml(s){
      var span=document.createElement('span');
      span.textContent=String(s||'');
      return span.innerHTML;
    }
    document.addEventListener('submit', function(e){
      var form=e.target.closest('form.filters');
      if(CURRENT_PAGE==='shop' && form){
        e.preventDefault();
        var data=new FormData(form);
        var params=new URLSearchParams(data);
        params.set('ajax','1');
        var url='<?= base_url('index.php?page=shop') ?>' + '&' + params.toString();
        fetch(url, { headers:{ 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' } })
          .then(function(r){ return r.json(); })
          .then(function(j){ if(j && j.ok){ renderShop(j); } });
      }
    });
  })();
  </script>
  <main>
