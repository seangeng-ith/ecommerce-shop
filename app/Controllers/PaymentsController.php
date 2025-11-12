<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Helpers;

class PaymentsController extends Controller {
  public function index(): void {
    if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
    $user = $_SESSION['user'] ?? null;
    $email = (string)($user['email'] ?? '');
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $order = null;
    if ($email !== '' && $id > 0) {
      try {
        $db = Database::getInstance();
        $rowU = $db->fetch('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
        $uid = (int)($rowU['id'] ?? 0);
        $order = $db->fetch('SELECT id, user_id, total_price, status, created_at FROM orders WHERE id = ? LIMIT 1', [$id]);
        if (!$order || (int)($order['user_id'] ?? 0) !== $uid) {
          $order = null;
        }
        if ($order && (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') && isset($_POST['capture'])) {
          $txn = isset($_POST['transaction_id']) ? trim((string)$_POST['transaction_id']) : '';
          if ($txn !== '') {
            $exists = $db->fetch('SELECT id FROM payments WHERE order_id = ? LIMIT 1', [$id]);
            if (!$exists) {
              $db->query('INSERT INTO payments (order_id, transaction_id, created_at) VALUES (?, ?, NOW())', [$id, $txn]);
            }
            $db->query('UPDATE orders SET status = ? WHERE id = ?', ['completed', $id]);
            header('Location: ' . Helpers::base_url('index.php?page=orders'));
            exit;
          }
        }
      } catch (\Throwable $e) {}
    }
    $this->view('payment', ['order' => $order]);
  }
}