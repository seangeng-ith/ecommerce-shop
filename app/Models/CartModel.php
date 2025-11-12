<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
class CartModel {
  public static function start(): void { if (session_status() !== PHP_SESSION_ACTIVE) session_start(); if (!isset($_SESSION['cart'])) $_SESSION['cart']=[]; }
  public static function add(string $id, int $qty=1): void {
    self::start();
    $n = max(1,$qty);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $n;
    self::persist((int)$id, $n);
  }
  public static function set(string $id, int $qty): void {
    self::start();
    $q = max(0, $qty);
    if ($q<=0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]= $q;
    $uid = self::userId();
    if ($uid > 0) {
      try {
        $db = Database::getInstance();
        $orderId = self::ensureCartOrderId();
        if ($orderId <= 0) return;
        $pid = (int)$id;
        if ($q <= 0) {
          $db->query('DELETE FROM order_items WHERE order_id = ? AND product_id = ?', [$orderId, $pid]);
        } else {
          $exists = $db->fetch('SELECT id FROM order_items WHERE order_id = ? AND product_id = ? LIMIT 1', [$orderId, $pid]);
          if ($exists && isset($exists['id'])) {
            $db->query('UPDATE order_items SET quantity = ? WHERE id = ?', [$q, (int)$exists['id']]);
          } else {
            $prod = $db->fetch('SELECT name,image FROM products WHERE id = ? LIMIT 1', [$pid]);
            if ($prod) {
              $db->query('INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, user_id, order_date) VALUES (?,?,?,?,?,?,NOW())', [
                $orderId,
                $pid,
                (string)($prod['name'] ?? ''),
                (string)($prod['image'] ?? ''),
                $q,
                $uid
              ]);
            }
          }
        }
        self::updateOrderTotal($orderId);
      } catch (\Throwable $e) {
      }
    }
  }
  public static function remove(string $id): void {
    self::start();
    unset($_SESSION['cart'][$id]);
    $uid = self::userId();
    if ($uid > 0) {
      try {
        $db = Database::getInstance();
        $orderId = self::ensureCartOrderId();
        if ($orderId <= 0) return;
        $db->query('DELETE FROM order_items WHERE order_id = ? AND product_id = ?', [$orderId, (int)$id]);
        self::updateOrderTotal($orderId);
      } catch (\Throwable $e) {
      }
    }
  }
  public static function clear(): void {
    self::start();
    $_SESSION['cart']=[];
    $uid = self::userId();
    if ($uid > 0) {
      try {
        $db = Database::getInstance();
        $orderId = self::ensureCartOrderId();
        if ($orderId <= 0) return;
        $db->query('DELETE FROM order_items WHERE order_id = ?', [$orderId]);
        $db->query('UPDATE orders SET total_price = 0 WHERE id = ?', [$orderId]);
        unset($_SESSION['cart_order_id']);
      } catch (\Throwable $e) {
      }
    }
  }
  public static function items(array $catalog): array {
    self::start();
    $uid = self::userId();
    if ($uid > 0) {
      try {
        $db = Database::getInstance();
        $orderId = self::ensureCartOrderId();
        if ($orderId <= 0) return [];
        $rows = $db->fetchAll('SELECT oi.product_id AS id, oi.product_name AS name, oi.product_image AS image, oi.quantity AS qty, p.price FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?', [$orderId]);
        $items = [];
        foreach ($rows as $r) {
          $r['subtotal'] = ((float)($r['price'] ?? 0)) * (int)($r['qty'] ?? 0);
          $items[] = $r;
        }
        $_SESSION['cart'] = [];
        foreach ($rows as $r) { $_SESSION['cart'][(string)$r['id']] = (int)($r['qty'] ?? 0); }
        return $items;
      } catch (\Throwable $e) {
      }
    }
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
  public static function count(): int {
    self::start();
    $uid = self::userId();
    if ($uid > 0) {
      try {
        $db = Database::getInstance();
        $orderId = self::ensureCartOrderId();
        if ($orderId <= 0) return 0;
        $row = $db->fetch('SELECT COALESCE(SUM(quantity),0) AS c FROM order_items WHERE order_id = ?', [$orderId]);
        return (int)($row['c'] ?? 0);
      } catch (\Throwable $e) {
        return 0;
      }
    }
    $sum = 0;
    foreach(($_SESSION['cart'] ?? []) as $id=>$qty){ $sum += (int)$qty; }
    return $sum;
  }
  private static function userId(): int {
    self::start();
    $email = $_SESSION['user']['email'] ?? null;
    if (!$email) return 0;
    try {
      $db = Database::getInstance();
      $row = $db->fetch('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
      return (int)($row['id'] ?? 0);
    } catch (\Throwable $e) {
      return 0;
    }
  }
  private static function ensureCartOrderId(): int {
    self::start();
    $oid = (int)($_SESSION['cart_order_id'] ?? 0);
    if ($oid > 0) return $oid;
    try {
      $db = Database::getInstance();
      $uid = self::userId();
      if ($uid <= 0) return 0;
      $row = $db->fetch('SELECT id FROM orders WHERE user_id = ? AND status = ? ORDER BY id DESC LIMIT 1', [$uid, 'cart']);
      if ($row && isset($row['id'])) {
        $oid = (int)$row['id'];
      } else {
        $db->query('INSERT INTO orders (user_id,total_price,status,created_at) VALUES (?,?,?,NOW())', [$uid, 0, 'cart']);
        $oid = (int)$db->lastInsertId();
      }
      $_SESSION['cart_order_id'] = $oid;
      return $oid;
    } catch (\Throwable $e) {
      return 0;
    }
  }
  private static function persist(int $productId, int $qty): void {
    try {
      $db = Database::getInstance();
      $orderId = self::ensureCartOrderId();
      if ($orderId <= 0) return;
      $prod = $db->fetch('SELECT name,image FROM products WHERE id = ? LIMIT 1', [$productId]);
      if (!$prod) return;
      $existing = $db->fetch('SELECT id,quantity FROM order_items WHERE order_id = ? AND product_id = ? LIMIT 1', [$orderId, $productId]);
      if ($existing && isset($existing['id'])) {
        $db->query('UPDATE order_items SET quantity = quantity + ? WHERE id = ?', [$qty, (int)$existing['id']]);
      } else {
        $db->query('INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, user_id, order_date) VALUES (?,?,?,?,?,?,NOW())', [
          $orderId,
          $productId,
          (string)($prod['name'] ?? ''),
          (string)($prod['image'] ?? ''),
          $qty,
          self::userId()
        ]);
      }
      self::updateOrderTotal($orderId);
    } catch (\Throwable $e) {
    }
  }
  private static function updateOrderTotal(int $orderId): void {
    try {
      $db = Database::getInstance();
      $row = $db->fetch('SELECT COALESCE(SUM(oi.quantity * p.price),0) AS t FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?', [$orderId]);
      $t = (float)($row['t'] ?? 0);
      $db->query('UPDATE orders SET total_price = ? WHERE id = ?', [$t, $orderId]);
    } catch (\Throwable $e) {
    }
  }
  public static function mergeSessionIntoUserCart(): void {
    self::start();
    $uid = self::userId();
    if ($uid <= 0) return;
    foreach (($_SESSION['cart'] ?? []) as $id=>$qty) {
      self::set((string)$id, (int)$qty);
    }
  }
  public static function finalize(): int {
    self::start();
    try {
      $db = Database::getInstance();
      $orderId = self::ensureCartOrderId();
      if ($orderId <= 0) return 0;
      self::updateOrderTotal($orderId);
      $db->query('UPDATE orders SET status = ? WHERE id = ?', ['on_hold', $orderId]);
      $_SESSION['cart'] = [];
      unset($_SESSION['cart_order_id']);
      return $orderId;
    } catch (\Throwable $e) {
      return 0;
    }
  }
}
