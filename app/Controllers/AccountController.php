<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\UserModel;

class AccountController extends Controller
{

  public function index()
  {
    if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
    $user = $_SESSION['user'] ?? null;
    $pw_success = false;
    $pw_error = '';
    $orders = [];
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      $current = isset($_POST['current_password']) ? (string)$_POST['current_password'] : '';
      $new = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
      $confirm = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';
      if (!$user || !isset($user['email'])) {
        $pw_error = 'Please login to change password.';
      } elseif ($current === '' || $new === '' || $confirm === '') {
        $pw_error = 'Please fill all fields.';
      } elseif (strlen($new) < 6) {
        $pw_error = 'Password must be at least 6 characters.';
      } elseif ($new !== $confirm) {
        $pw_error = 'New password and confirmation do not match.';
      } else {
        $um = new UserModel();
        $valid = $um->verify((string)$user['email'], $current);
        if (!$valid) {
          $pw_error = 'Current password is incorrect.';
        } else {
          $ok = $um->changePassword((string)$user['email'], $new);
          $pw_success = $ok;
          if (!$ok) { $pw_error = 'Unable to update password.'; }
        }
      }
    }
    try {
      $email = (string)($user['email'] ?? '');
      if ($email !== '') {
        $db = Database::getInstance();
        $row = $db->fetch('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
        $uid = (int)($row['id'] ?? 0);
        if ($uid > 0) {
          $orders = $db->fetchAll('SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? AND status <> ? ORDER BY id DESC LIMIT 5', [$uid, 'cart']);
        }
      }
    } catch (\Throwable $e) {}
    $this->view('account', ['user' => $user, 'pw_success' => $pw_success, 'pw_error' => $pw_error, 'orders' => $orders]);
  }
  public function orders(): void
  {
    if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
    $user = $_SESSION['user'] ?? null;
    $email = (string)($user['email'] ?? '');
    $orders = [];
    if ($email !== '') {
      try {
        $db = Database::getInstance();
        $row = $db->fetch('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
        $uid = (int)($row['id'] ?? 0);
        if ($uid > 0) {
          $orders = $db->fetchAll('SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? AND status <> ? ORDER BY id DESC', [$uid, 'cart']);
        }
      } catch (\Throwable $e) {}
    }
    $this->view('orders', ['orders' => $orders]);
  }
  public function order(): void
  {
    if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
    $user = $_SESSION['user'] ?? null;
    $email = (string)($user['email'] ?? '');
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $order = null;
    $items = [];
    if ($email !== '' && $id > 0) {
      try {
        $db = Database::getInstance();
        $rowU = $db->fetch('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
        $uid = (int)($rowU['id'] ?? 0);
        $order = $db->fetch('SELECT id, user_id, total_price, status, created_at FROM orders WHERE id = ? LIMIT 1', [$id]);
        if ($order && (int)($order['user_id'] ?? 0) === $uid) {
          $items = $db->fetchAll('SELECT oi.product_id AS id, oi.product_name AS name, oi.product_image AS image, oi.quantity AS qty, p.price FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?', [$id]);
        } else {
          $order = null; $items = [];
        }
      } catch (\Throwable $e) {}
    }
    $this->view('order', ['order' => $order, 'items' => $items]);
  }
}
