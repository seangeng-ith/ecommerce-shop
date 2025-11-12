<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CartModel;

class CheckoutController extends Controller {
    
    public function index() {
        $pm = new ProductModel();
        $items = CartModel::items($pm->all());
        $total = 0.0; foreach ($items as $it) { $total += (float)($it['subtotal'] ?? 0); }
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $orderId = CartModel::finalize();
            if ($orderId > 0) {
                header('Location: ' . \App\Core\Helpers::base_url('index.php?page=orders'));
                exit;
            }
        }
        $this->view('checkout', ['items' => $items, 'total' => $total]);
    }
}