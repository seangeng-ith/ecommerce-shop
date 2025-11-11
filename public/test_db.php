<?php
require_once __DIR__ . '/app/Core/Database.php';

try {
    $db = \App\Core\Database::getInstance();
    echo "✓ Database connection successful!";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage();
}
