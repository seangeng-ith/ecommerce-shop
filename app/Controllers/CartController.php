<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CartModel;
use App\Core\Helpers;
class CartController extends Controller {
  public function index(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      if (isset($_POST['add_to_cart'])) {
        CartModel::add((string)$_POST['add_to_cart'], isset($_POST['qty'])? (int)$_POST['qty'] : 1);
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
      header('Location: ' . Helpers::base_url('index.php?page=cart')); exit;
    }
    $pm = new ProductModel();
    $items = CartModel::items($pm->all());
    $this->view('cart', ['items'=>$items]);
  }
}
