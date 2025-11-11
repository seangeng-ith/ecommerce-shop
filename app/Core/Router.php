<?php
declare(strict_types=1);
namespace App\Core;
class Router {
  public function dispatch(): void {
    $page = $_GET['page'] ?? 'home';
    $map = [
      'home' => ['App\\Controllers\\HomeController','index'],
      'shop' => ['App\\Controllers\\ShopController','index'],
      'product' => ['App\\Controllers\\ProductController','show'],
      'cart' => ['App\\Controllers\\CartController','index'],
      'contact' => ['App\\Controllers\\ContactController','index'],
      'blog' => ['App\\Controllers\\BlogController','index'],
    ];
    if (!isset($map[$page])) $map[$page] = $map['home'];
    [$class,$method] = $map[$page];
    (new $class())->$method();
  }
}
