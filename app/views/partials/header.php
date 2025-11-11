<?php
$conf = require __DIR__ . '/../../../config/config.php';
$appName = htmlspecialchars($conf['app_name'] ?? 'Eshop', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $appName ?></title>
<link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head><body>
<header class="topbar">
  <div class="container nav">
    <a class="brand" href="<?= base_url('index.php') ?>"><span><?= $appName ?></span></a>
    <nav>
      <a href="<?= base_url('index.php?page=home') ?>" class="<?= ($_GET['page']??'home')==='home'?'active':'' ?>">Home</a>
      <a href="<?= base_url('index.php?page=shop') ?>" class="<?= ($_GET['page']??'')==='shop'?'active':'' ?>">Shop</a>
      <a href="<?= base_url('index.php?page=blog') ?>" class="<?= ($_GET['page']??'')==='blog'?'active':'' ?>">Blog</a>
      <a href="<?= base_url('index.php?page=contact') ?>" class="<?= ($_GET['page']??'')==='contact'?'active':'' ?>">Contact</a>
    </nav>
    <div class="icons">
      <a class="cart" href="<?= base_url('index.php?page=cart') ?>">🛒 <?= cart_count() ?></a>
      <span>👤</span>
    </div>
  </div>
</header>
<main>
