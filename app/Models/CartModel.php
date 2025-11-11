<?php
declare(strict_types=1);
namespace App\Models;
class CartModel {
  public static function start(): void { if (session_status() !== PHP_SESSION_ACTIVE) session_start(); if (!isset($_SESSION['cart'])) $_SESSION['cart']=[]; }
  public static function add(string $id, int $qty=1): void { self::start(); $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + max(1,$qty); }
  public static function set(string $id, int $qty): void { self::start(); if ($qty<=0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]= $qty; }
  public static function remove(string $id): void { self::start(); unset($_SESSION['cart'][$id]); }
  public static function clear(): void { self::start(); $_SESSION['cart']=[]; }
  public static function items(array $catalog): array {
    self::start();
    $items=[];
    foreach(($_SESSION['cart'] ?? []) as $id=>$qty){
      foreach($catalog as $p){
        if ((string)$p['id']===(string)$id){
          $p['qty']=$qty; $p['subtotal']=$qty*$p['price']; $items[]=$p; break;
        }
      }
    }
    return $items;
  }
}
