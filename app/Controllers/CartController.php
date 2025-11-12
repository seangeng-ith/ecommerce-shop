<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CartModel;
use App\Core\Helpers;
class CartController extends Controller {
  private function isAjax(): bool {
    $x = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
    $a = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
    return $x === 'xmlhttprequest' || (function_exists('str_contains') ? str_contains($a, 'application/json') : false) || isset($_POST['ajax']) || isset($_GET['ajax']);
  }
  public function index(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      if (isset($_POST['add_to_cart'])) {
        if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
        $u = $_SESSION['user'] ?? null;
        if (!$u) {
          $redirTarget = (string)($_POST['redirect'] ?? ('index.php?page=product&id=' . urlencode((string)$_POST['add_to_cart'])));
          $loginUrl = Helpers::base_url('index.php?page=login&redirect=' . urlencode($redirTarget));
          if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok'=>false,'require_login'=>true,'login_url'=>$loginUrl]);
            exit;
          }
          header('Location: ' . $loginUrl); exit;
        }
        CartModel::add((string)$_POST['add_to_cart'], isset($_POST['qty'])? (int)$_POST['qty'] : 1);
        if ($this->isAjax()) {
          header('Content-Type: application/json');
          echo json_encode(['ok'=>true,'count'=>CartModel::count()]);
          exit;
        }
        $redirect = $_POST['redirect'] ?? Helpers::base_url('index.php?page=cart');
        header('Location: ' . $redirect); exit;
      }
      if (isset($_POST['set_qty'])) {
        foreach($_POST['qty'] ?? [] as $id=>$q){ CartModel::set((string)$id, (int)$q); }
        header('Location: ' . Helpers::base_url('index.php?page=cart')); exit;
      }
      if (isset($_POST['clear_cart'])) {
        CartModel::clear();
        header('Location: ' . Helpers::base_url('index.php?page=cart')); exit;
      }
    }
    if (isset($_GET['remove'])) {
      CartModel::remove((string)$_GET['remove']);
      if ($this->isAjax()) {
        $pm = new ProductModel();
        $items = CartModel::items($pm->all());
        $total = 0.0; foreach ($items as $it) { $total += (float)($it['subtotal'] ?? 0); }
        header('Content-Type: application/json');
        echo json_encode(['ok'=>true,'count'=>CartModel::count(),'total'=>$total,'total_fmt'=>Helpers::money((float)$total)]);
        exit;
      }
      header('Location: ' . Helpers::base_url('index.php?page=cart')); exit;
    }
    $pm = new ProductModel();
    $items = CartModel::items($pm->all());
    $this->view('cart', ['items'=>$items]);
  }
}
