<?php
declare(strict_types=1);
namespace App\Core;
class Helpers {
  public static function base_url(string $path=''): string {
    static $conf = null;
    if ($conf === null) $conf = require __DIR__ . '/../../config/config.php';
    $base = rtrim($conf['base_url'] ?? '', '/');
    $path = ltrim($path, '/');
    return ($base ? $base . '/' : '') . $path;
  }
  public static function money(float $n): string { return '$' . number_format($n, 2); }
}
