<?php
if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        // try to use project config if available
        $confPath = __DIR__ . '/../../config/config.php';
        $base = '';

        if (file_exists($confPath)) {
            $cfg = require $confPath;
            $base = rtrim($cfg['base_url'] ?? '', '/');
        }

        // fallback to auto-detected host if config not set
        if ($base === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $base = $scheme . '://' . $host;
        }

        $path = ltrim($path, '/');
        return $base . ($path !== '' ? '/' . $path : '');
    }
}