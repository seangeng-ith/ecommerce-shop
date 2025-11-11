<?php
function cart_start() { if (session_status() !== PHP_SESSION_ACTIVE) session_start(); if (!isset($_SESSION['cart'])) $_SESSION['cart']=[]; }

function cart_add(string $id, int $qty=1): void {
  cart_start();
  if ($qty < 1) $qty = 1;
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
}

function cart_set(string $id, int $qty): void {
  cart_start();
  if ($qty <= 0) { unset($_SESSION['cart'][$id]); return; }
  $_SESSION['cart'][$id] = $qty;
}

function cart_remove(string $id): void {
  cart_start();
  unset($_SESSION['cart'][$id]);
}

function cart_count(): int {
  cart_start();
  return array_sum($_SESSION['cart']);
}

function cart_items(array $catalog): array {
  cart_start();
  $items = [];
  foreach(($_SESSION['cart'] ?? []) as $id=>$qty){
    foreach($catalog as $p){
      if ((string)$p['id'] === (string)$id){
        $p['qty'] = $qty;
        $p['subtotal'] = $qty * $p['price'];
        $items[] = $p; break;
      }
    }
  }
  return $items;
}

function money($n){ return '$' . number_format((float)$n, 2); }
