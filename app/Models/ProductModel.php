<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
class ProductModel {
  private static ?array $cacheAll = null;
  private static array $cacheList = [];
  public function all(): array {
    try {
      $db = Database::getInstance();
      $rows = $db->fetchAll('SELECT id,name,price,category,rating,image,gallery,description FROM products');
      foreach ($rows as &$r) {
        if (isset($r['gallery']) && is_string($r['gallery'])) {
          $g = json_decode($r['gallery'], true);
          if (is_array($g)) $r['gallery'] = $g;
        }
      }
      self::$cacheAll = $rows;
      return $rows;
    } catch (\Throwable $e) {
      if (self::$cacheAll !== null) return self::$cacheAll;
      $path = __DIR__ . '/../Data/products.json';
      self::$cacheAll = json_decode(@file_get_contents($path), true) ?? [];
      return self::$cacheAll;
    }
  }
  public function find(string $id): ?array {
    try {
      $db = Database::getInstance();
      $row = $db->fetch('SELECT id,name,price,category,rating,image,gallery,description FROM products WHERE id = ? LIMIT 1', [$id]);
      if (!$row) return null;
      if (isset($row['gallery']) && is_string($row['gallery'])) {
        $g = json_decode($row['gallery'], true);
        if (is_array($g)) $row['gallery'] = $g;
      }
      return $row;
    } catch (\Throwable $e) {
      $all = self::$cacheAll ?? (self::$cacheAll = (json_decode(@file_get_contents(__DIR__ . '/../Data/products.json'), true) ?? []));
      foreach ($all as $p) { if ((string)$p['id'] === (string)$id) return $p; }
      return null;
    }
  }
  public function filterSortPaginate(array $params): array {
    $key = md5(json_encode([
      'cat'=>$params['cat'] ?? 'all',
      'min'=>$params['min'] ?? null,
      'max'=>$params['max'] ?? null,
      'sort'=>$params['sort'] ?? 'popular',
      'p'=>$params['p'] ?? 1,
    ]));
    if (isset(self::$cacheList[$key])) return self::$cacheList[$key];
    try {
      $db = Database::getInstance();
      $where = [];
      $bind = [];
      $cat = $params['cat'] ?? 'all';
      if ($cat !== 'all' && $cat !== '') { $where[] = 'category = ?'; $bind[] = $cat; }
      $min = isset($params['min']) && $params['min'] !== '' ? (float)$params['min'] : null;
      $max = isset($params['max']) && $params['max'] !== '' ? (float)$params['max'] : null;
      if ($min !== null) { $where[] = 'price >= ?'; $bind[] = $min; }
      if ($max !== null) { $where[] = 'price <= ?'; $bind[] = $max; }
      $sort = $params['sort'] ?? 'popular';
      $order = 'rating DESC';
      if ($sort === 'price_asc') $order = 'price ASC';
      elseif ($sort === 'price_desc') $order = 'price DESC';
      elseif ($sort === 'rating') $order = 'rating DESC';
      elseif ($sort === 'new') $order = 'id DESC';
      $page = max(1, (int)($params['p'] ?? 1));
      $per = 12;
      $offset = ($page - 1) * $per;
      $sqlWhere = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
      $totalRow = $db->fetch('SELECT COUNT(*) AS c FROM products ' . $sqlWhere, $bind);
      $total = (int)($totalRow['c'] ?? 0);
      $pages = max(1, (int)ceil($total / $per));
      $items = $db->fetchAll('SELECT id,name,price,category,rating,image,gallery,description FROM products ' . $sqlWhere . ' ORDER BY ' . $order . ' LIMIT ' . $per . ' OFFSET ' . $offset, $bind);
      foreach ($items as &$r) {
        if (isset($r['gallery']) && is_string($r['gallery'])) {
          $g = json_decode($r['gallery'], true);
          if (is_array($g)) $r['gallery'] = $g;
        }
      }
      $out = compact('items','pages','page','cat','total','per');
      self::$cacheList[$key] = $out;
      return $out;
    } catch (\Throwable $e) {
      $all = self::$cacheAll ?? (self::$cacheAll = (json_decode(@file_get_contents(__DIR__ . '/../Data/products.json'), true) ?? []));
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
      $per = 12;
      $total = count($all);
      $pages = max(1, (int)ceil($total / $per));
      $offset = ($page-1)*$per;
      $items = array_slice($all, $offset, $per);
      $out = compact('items','pages','page','cat','total','per');
      self::$cacheList[$key] = $out;
      return $out;
    }
  }
}
