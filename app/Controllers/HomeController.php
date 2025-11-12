<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ProductModel;
class HomeController extends Controller {
  private ProductModel $pm;

  public function __construct(ProductModel $pm)
  {
    $this->pm = $pm;
  }

  public function index(): void {
    $all = $this->pm->all();
    $feat = array_slice($all, 0, 4);
    $clothes = array_slice(($this->pm->filterSortPaginate(['cat' => 'clothes'])['items'] ?? []), 0, 4);
    $bags = array_slice(($this->pm->filterSortPaginate(['cat' => 'bags'])['items'] ?? []), 0, 4);
    $shoes = array_slice(($this->pm->filterSortPaginate(['cat' => 'shoes'])['items'] ?? []), 0, 4);
    $this->view('home', ['feat' => $feat, 'clothes' => $clothes, 'bags' => $bags, 'shoes' => $shoes]);
  }
}
