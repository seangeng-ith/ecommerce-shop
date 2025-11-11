<?php
function base_url(string $path=''): string {
  static $conf = null;
  if ($conf === null) $conf = require __DIR__ . '/../../config/config.php';
  $base = rtrim($conf['base_url'] ?? '', '/');
  $path = ltrim($path, '/');
  return $base . '/' . $path;
}
