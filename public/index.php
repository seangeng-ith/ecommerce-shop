<?php
declare(strict_types=1);
require __DIR__ . '/../app/helpers/url.php';
require __DIR__ . '/../app/helpers/cart.php';
session_start();

// Actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  if (isset($_POST['add_to_cart'])) {
    $id = (string)$_POST['add_to_cart'];
    $qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;
    cart_add($id, $qty);
    $redirect = $_POST['redirect'] ?? base_url('index.php?page=cart');
    header('Location: ' . $redirect);
    exit;
  }
  if (isset($_POST['update_cart'])) {
    foreach($_POST['qty'] ?? [] as $pid=>$q){
      cart_set((string)$pid, (int)$q);
    }
    header('Location: ' . base_url('index.php?page=cart'));
    exit;
  }
}
if (isset($_GET['remove'])) {
  cart_remove((string)$_GET['remove']);
  header('Location: ' . base_url('index.php?page=cart'));
  exit;
}

// Routing
$page = $_GET['page'] ?? 'home';
$valid = ['home','shop','contact','blog','product','cart'];
if (!in_array($page, $valid, true)) $page = 'home';

include __DIR__ . '/../app/views/partials/header.php';
include __DIR__ . '/../app/views/' . $page . '.php';
include __DIR__ . '/../app/views/partials/footer.php';
