<?php

declare(strict_types=1);
session_start();
spl_autoload_register(function ($class) {
  $prefix = 'App\\';
  if (str_starts_with($class, $prefix)) {
    $path = __DIR__ . '/../' . str_replace('App\\', 'app/', $class) . '.php';
    $path = str_replace('\\', '/', $path);
    if (file_exists($path)) require $path;
  }
});

use App\Core\Router;

(new Router())->dispatch();
