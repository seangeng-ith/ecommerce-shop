<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
class ProductController extends Controller {
  public function show(): void {
    $pm = new ProductModel();
    $id = $_GET['id'] ?? '';
    $product = $pm->find((string)$id);
    $this->view('product', ['product'=>$product]);
  }
}
