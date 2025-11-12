<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
  public function dispatch(): void
  {
    if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
    $page = $_GET['page'] ?? 'home';
    $map = [
      'home' => ['App\\Controllers\\HomeController', 'index'],
      'shop' => ['App\\Controllers\\ShopController', 'index'],
      'product' => ['App\\Controllers\\ProductController', 'show'],
      'cart' => ['App\\Controllers\\CartController', 'index'],
      'contact' => ['App\\Controllers\\ContactController', 'index'],
      'blog' => ['App\\Controllers\\BlogController', 'index'],
      'login' => ['App\\Controllers\\LoginController', 'index'],
      'forgot' => ['App\\Controllers\\LoginController', 'forgot'],
      'register' => ['App\\Controllers\\RegisterController', 'index'],
      'account' => ['App\\Controllers\\AccountController', 'index'],
      'orders' => ['App\\Controllers\\AccountController', 'orders'],
      'order' => ['App\\Controllers\\AccountController', 'order'],
      'payment' => ['App\\Controllers\\PaymentsController', 'index'],
      'checkout' => ['App\\Controllers\\CheckoutController', 'index'],
      'logout' => ['App\\Controllers\\LoginController', 'logout'],
    ];
    $protected = ['account','checkout','orders','order','payment'];
    if (in_array($page, $protected, true)) {
      $u = $_SESSION['user'] ?? null;
      if (!$u) {
        $redir = \App\Core\Helpers::base_url('index.php?page=login&redirect=' . urlencode('index.php?page=' . $page));
        header('Location: ' . $redir);
        exit;
      }
    }
    if (!isset($map[$page])) $map[$page] = $map['home'];
    [$class, $method] = $map[$page];
    $controller = $this->resolve($class);
    $controller->$method();
  }

  public function get($path, $controller)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === $path) {
      // Route logic
    }
  }

  private function resolve(string $class)
  {
    $rc = new \ReflectionClass($class);
    $ctor = $rc->getConstructor();
    if (!$ctor || $ctor->getNumberOfParameters() === 0) {
      return new $class();
    }
    $args = [];
    foreach ($ctor->getParameters() as $param) {
      $type = $param->getType();
      if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
        $depClass = $type->getName();
        $args[] = $this->resolve($depClass);
      } else {
        $args[] = null;
      }
    }
    return $rc->newInstanceArgs($args);
  }
}
