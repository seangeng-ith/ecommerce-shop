<?php
declare(strict_types=1);
namespace App\Models;
class ProductModel {
  private array $products;
  public function __construct() {
    $path = __DIR__ . '/../Data/products.json';
    $this->products = json_decode(file_get_contents($path), true) ?? [];
  }
  public function all(): array { return $this->products; }
  public function find(string $id): ?array {
    foreach($this->products as $p){ if ((string)$p['id']===(string)$id) return $p; }
    return null;
  }
  public function filterSortPaginate(array $params): array {
    $all = $this->products;
    $cat = $params['cat'] ?? 'all';
    $allowedCats = ['all','clothes','bags','shoes'];
    if (!in_array($cat, $allowedCats, true)) { $cat = 'all'; }
    if ($cat !== 'all') $all = array_values(array_filter($all, fn($p)=>$p['category']===$cat));
    $min = isset($params['min']) && $params['min'] !== '' ? (float)$params['min'] : null;
    $max = isset($params['max']) && $params['max'] !== '' ? (float)$params['max'] : null;
    if ($min !== null) $all = array_values(array_filter($all, fn($p)=>$p['price'] >= $min));
    if ($max !== null) $all = array_values(array_filter($all, fn($p)=>$p['price'] <= $max));
    $sort = $params['sort'] ?? 'popular';
    usort($all, function($a,$b) use ($sort){
      return match($sort){
        'popular' => $b['rating'] <=> $a['rating'],
        'new' => $b['id'] <=> $a['id'],
        'price_asc' => $a['price'] <=> $b['price'],
        'price_desc' => $b['price'] <=> $a['price'],
        'rating' => $b['rating'] <=> $a['rating'],
        default => $b['rating'] <=> $a['rating'],
      };
    });
    $page = max(1, (int)($params['p'] ?? 1));
    $per = 8;
    $total = count($all);
    $pages = max(1, (int)ceil($total / $per));
    $offset = ($page-1)*$per;
    $items = array_slice($all, $offset, $per);
    return compact('items','pages','page','cat','total','per');
  }
}
