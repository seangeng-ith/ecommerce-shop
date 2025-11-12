<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
use App\Core\Helpers;
class ShopController extends Controller {
  private ProductModel $pm;

  public function __construct(ProductModel $pm)
  {
    $this->pm = $pm;
  }

  private function isAjax(): bool {
    $x = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
    $a = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
    return $x === 'xmlhttprequest' || (function_exists('str_contains') ? str_contains($a, 'application/json') : false) || isset($_GET['ajax']) || isset($_POST['ajax']);
  }
  public function index(): void {
    $data = $this->pm->filterSortPaginate($_GET);
    if ($this->isAjax()) {
      header('Content-Type: application/json');
      $base = Helpers::base_url('');
      $items = [];
      foreach (($data['items'] ?? []) as $p) {
        $items[] = [
          'id' => $p['id'],
          'name' => $p['name'],
          'price' => (float)$p['price'],
          'rating' => (int)$p['rating'],
          'image' => $p['image'],
          'product_url' => $base . 'index.php?page=product&id=' . urlencode((string)$p['id']),
          'image_url' => $base . 'img/' . (string)$p['image'],
        ];
      }
      echo json_encode([
        'ok' => true,
        'items' => $items,
        'page' => (int)($data['page'] ?? 1),
        'pages' => (int)($data['pages'] ?? 1)
      ]);
      exit;
    }
    $this->view('shop', $data);
  }
}
