<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
class ShopController extends Controller {
  public function index(): void {
    $pm = new ProductModel();
    $data = $pm->filterSortPaginate($_GET);
    $this->view('shop', $data);
  }
}
