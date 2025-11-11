<?php
declare(strict_types=1);
namespace App\Core;
class View {
  public static function render(string $view, array $params=[]): void {
    extract($params);
    include __DIR__ . '/../Views/partials/header.php';
    include __DIR__ . '/../Views/' . $view . '.php';
    include __DIR__ . '/../Views/partials/footer.php';
  }
}
