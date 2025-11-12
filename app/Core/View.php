<?php
declare(strict_types=1);

namespace {
  if (!function_exists('base_url')) {
    function base_url(string $path = ''): string {
      return \App\Core\Helpers::base_url($path);
    }
  }
  if (!function_exists('money')) {
    function money($n): string {
      return \App\Core\Helpers::money((float)$n);
    }
  }
}

namespace App\Core {
  class View {
    public static function render(string $view, array $params=[]): void {
      extract($params);
      include __DIR__ . '/../Views/partials/header.php';
      include __DIR__ . '/../Views/' . $view . '.php';
      include __DIR__ . '/../Views/partials/footer.php';
    }
  }
}
