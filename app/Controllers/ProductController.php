<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
class ProductController extends Controller {
  private ProductModel $pm;

  public function __construct(ProductModel $pm)
  {
    $this->pm = $pm;
  }

  public function show(): void {
    $id = isset($_GET['id']) ? (string)$_GET['id'] : '';
    $product = $id !== '' ? $this->pm->find($id) : null;
    $related = [];
    if ($product && isset($product['category'])) {
      $all = $this->pm->all();
      $pool = array_values(array_filter($all, function($p) use ($product){
        return isset($p['category']) && $p['category'] === $product['category'] && (string)($p['id'] ?? '') !== (string)$product['id'];
      }));
      if (count($pool) > 1) { shuffle($pool); }
      $related = array_slice($pool, 0, 4);
    }
    $this->view('product', ['product' => $product, 'related' => $related]);
  }
}
