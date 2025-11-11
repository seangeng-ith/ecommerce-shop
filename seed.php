<?php
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Seeder.php';

try {
    echo "Starting database seeding...\n\n";
    
    $seeder = new \App\Core\Seeder();
    $seeder->run();
    
    echo "\n✓ All tables seeded successfully!\n";
} catch (Exception $e) {
    echo "✗ Seeding failed: " . $e->getMessage() . "\n";
}