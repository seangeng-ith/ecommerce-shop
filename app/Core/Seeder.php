<?php

namespace App\Core;

use PDO;

class Seeder
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function run()
    {
        $this->ensureSchema();
        $this->wipeData();
        $this->seedUsers();
        $this->seedProducts();
        $this->seedOrders();
        $this->seedOrderItems();
        $this->seedPayments();
    }

    private function ensureSchema()
    {
        $stmts = [
            "CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) CHARACTER SET utf8mb4",
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS name VARCHAR(100) NOT NULL",
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(255) NOT NULL",
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255) NOT NULL",
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",

            "CREATE TABLE IF NOT EXISTS products (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, category VARCHAR(50) NOT NULL, description TEXT NULL, image VARCHAR(255) NOT NULL, gallery TEXT NULL, price DECIMAL(10,2) NOT NULL, rating TINYINT NOT NULL DEFAULT 0) CHARACTER SET utf8mb4",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS name VARCHAR(255) NOT NULL",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS category VARCHAR(50) NOT NULL",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS description TEXT NULL",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS image VARCHAR(255) NOT NULL",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS gallery TEXT NULL",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS price DECIMAL(10,2) NOT NULL",
            "ALTER TABLE products ADD COLUMN IF NOT EXISTS rating TINYINT NOT NULL DEFAULT 0",
            "CREATE INDEX IF NOT EXISTS idx_products_category ON products(category)",
            "CREATE INDEX IF NOT EXISTS idx_products_price ON products(price)",
            "CREATE INDEX IF NOT EXISTS idx_products_rating ON products(rating)",
            "CREATE INDEX IF NOT EXISTS idx_products_cat_price_rating ON products(category, price, rating)",

            "CREATE TABLE IF NOT EXISTS orders (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, total_price DECIMAL(10,2) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) CHARACTER SET utf8mb4",
            "ALTER TABLE orders ADD COLUMN IF NOT EXISTS user_id INT NOT NULL",
            "ALTER TABLE orders ADD COLUMN IF NOT EXISTS total_price DECIMAL(10,2) NOT NULL",
            "ALTER TABLE orders ADD COLUMN IF NOT EXISTS status VARCHAR(50) NOT NULL",
            "ALTER TABLE orders ADD COLUMN IF NOT EXISTS created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",

            "CREATE TABLE IF NOT EXISTS order_items (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, product_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_image VARCHAR(255) NOT NULL, quantity INT NOT NULL, user_id INT NOT NULL, order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) CHARACTER SET utf8mb4",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS order_id INT NOT NULL",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS product_id INT NOT NULL",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS product_name VARCHAR(255) NOT NULL",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS product_image VARCHAR(255) NOT NULL",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS quantity INT NOT NULL",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS user_id INT NOT NULL",
            "ALTER TABLE order_items ADD COLUMN IF NOT EXISTS order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",

            "CREATE TABLE IF NOT EXISTS payments (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, transaction_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) CHARACTER SET utf8mb4",
            "ALTER TABLE payments ADD COLUMN IF NOT EXISTS order_id INT NOT NULL",
            "ALTER TABLE payments ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(255) NOT NULL",
            "ALTER TABLE payments ADD COLUMN IF NOT EXISTS created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
        ];
        foreach ($stmts as $sql) {
            $this->db->exec($sql);
        }
    }

    private function wipeData()
    {
        echo "Wiping existing data...\n";
        $this->db->exec("SET FOREIGN_KEY_CHECKS=0");
        $tables = ['order_items','payments','orders','products','users'];
        foreach ($tables as $t) {
            try {
                $this->db->exec("TRUNCATE TABLE `{$t}`");
            } catch (\Throwable $e) {
                $this->db->exec("DELETE FROM `{$t}`");
                $this->db->exec("ALTER TABLE `{$t}` AUTO_INCREMENT = 1");
            }
        }
        $this->db->exec("SET FOREIGN_KEY_CHECKS=1");
        echo "✓ Data wiped\n";
    }

    private function seedUsers()
    {
        echo "Seeding users table...\n";

        $users = [
            ['John Doe', 'john@email.com', 'password123'],
            ['Jane Smith', 'jane@email.com', 'password123'],
            ['Mike Johnson', 'mike@email.com', 'password123'],
        ];

        $sql = "INSERT INTO users (name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())";

        foreach ($users as $user) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $user[0],
                $user[1],
                password_hash($user[2], PASSWORD_DEFAULT)
            ]);
        }

        echo "✓ Users seeded successfully!\n";
    }

    private function seedProducts()
    {
        echo "Seeding products table...\n";

        $products = [
            ['name'=>'Shoes 1','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-1.jpg','gallery'=>['shoes-1.jpg','shoes-1.jpg','shoes-1.jpg','shoes-1.jpg'],'price'=>89.99,'rating'=>5],
            ['name'=>'Shoes 2','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-2.jpg','gallery'=>['shoes-2.jpg','shoes-2.jpg','shoes-2.jpg','shoes-2.jpg'],'price'=>92.99,'rating'=>5],
            ['name'=>'Shoes 3','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-3.jpg','gallery'=>['shoes-3.jpg','shoes-3.jpg','shoes-3.jpg','shoes-3.jpg'],'price'=>84.99,'rating'=>4],
            ['name'=>'Shoes 4','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-4.jpg','gallery'=>['shoes-4.jpg','shoes-4.jpg','shoes-4.jpg','shoes-4.jpg'],'price'=>99.99,'rating'=>5],
            ['name'=>'Shoes 5','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-5.jpg','gallery'=>['shoes-5.jpg','shoes-5.jpg','shoes-5.jpg','shoes-5.jpg'],'price'=>79.99,'rating'=>4],
            ['name'=>'Shoes 6','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-6.jpg','gallery'=>['shoes-6.jpg','shoes-6.jpg','shoes-6.jpg','shoes-6.jpg'],'price'=>87.99,'rating'=>4],

            ['name'=>'Clothes 1','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-1.jpg','gallery'=>['clothes-1.jpg','clothes-1.jpg','clothes-1.jpg','clothes-1.jpg'],'price'=>39.99,'rating'=>3],
            ['name'=>'Clothes 2','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-2.jpg','gallery'=>['clothes-2.jpg','clothes-2.jpg','clothes-2.jpg','clothes-2.jpg'],'price'=>41.99,'rating'=>4],
            ['name'=>'Clothes 3','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-3.jpg','gallery'=>['clothes-3.jpg','clothes-3.jpg','clothes-3.jpg','clothes-3.jpg'],'price'=>44.99,'rating'=>5],
            ['name'=>'Clothes 4','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-4.jpg','gallery'=>['clothes-4.jpg','clothes-4.jpg','clothes-4.jpg','clothes-4.jpg'],'price'=>49.99,'rating'=>4],
            ['name'=>'Clothes 5','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-5.jpg','gallery'=>['clothes-5.jpg','clothes-5.jpg','clothes-5.jpg','clothes-5.jpg'],'price'=>46.99,'rating'=>3],
            ['name'=>'Clothes 6','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-6.jpg','gallery'=>['clothes-6.jpg','clothes-6.jpg','clothes-6.jpg','clothes-6.jpg'],'price'=>52.99,'rating'=>4],

            ['name'=>'Bag 1','category'=>'bags','description'=>'Fashionable bag','image'=>'bags-1.jpg','gallery'=>['bags-1.jpg','bags-1.jpg','bags-1.jpg','bags-1.jpg'],'price'=>69.99,'rating'=>4],
            ['name'=>'Bag 2','category'=>'bags','description'=>'Fashionable bag','image'=>'bages-2.jpg','gallery'=>['bages-2.jpg','bages-2.jpg','bages-2.jpg','bages-2.jpg'],'price'=>64.99,'rating'=>3],
            ['name'=>'Bag 3','category'=>'bags','description'=>'Fashionable bag','image'=>'bags-3.jpg','gallery'=>['bags-3.jpg','bags-3.jpg','bags-3.jpg','bags-3.jpg'],'price'=>72.99,'rating'=>4],
            ['name'=>'Bag 4','category'=>'bags','description'=>'Fashionable bag','image'=>'bags-4.jpg','gallery'=>['bags-4.jpg','bags-4.jpg','bags-4.jpg','bags-4.jpg'],'price'=>75.99,'rating'=>4],
            ['name'=>'Bag 5','category'=>'bags','description'=>'Fashionable bag','image'=>'bages-5.jpg','gallery'=>['bages-5.jpg','bages-5.jpg','bages-5.jpg','bages-5.jpg'],'price'=>68.99,'rating'=>3],
            ['name'=>'Bag 6','category'=>'bags','description'=>'Fashionable bag','image'=>'bags-6.jpg','gallery'=>['bags-6.jpg','bags-6.jpg','bags-6.jpg','bags-6.jpg'],'price'=>79.99,'rating'=>5],
            ['name'=>'Bag 7','category'=>'bags','description'=>'Fashionable bag','image'=>'2.jpg','gallery'=>['2.jpg','2.jpg','2.jpg','2.jpg'],'price'=>58.99,'rating'=>3],

            ['name'=>'Shoes 7','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-6.jpg','gallery'=>['shoes-6.jpg','shoes-6.jpg','shoes-6.jpg','shoes-6.jpg'],'price'=>88.99,'rating'=>4],
            ['name'=>'Shoes 8','category'=>'shoes','description'=>'Comfortable athletic shoes','image'=>'shoes-5.jpg','gallery'=>['shoes-5.jpg','shoes-5.jpg','shoes-5.jpg','shoes-5.jpg'],'price'=>82.99,'rating'=>4],
            ['name'=>'Clothes 7','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-6.jpg','gallery'=>['clothes-6.jpg','clothes-6.jpg','clothes-6.jpg','clothes-6.jpg'],'price'=>54.99,'rating'=>4],
            ['name'=>'Clothes 8','category'=>'clothes','description'=>'Stylish everyday wear','image'=>'clothes-5.jpg','gallery'=>['clothes-5.jpg','clothes-5.jpg','clothes-5.jpg','clothes-5.jpg'],'price'=>48.99,'rating'=>3],
        ];

        $sql = "INSERT INTO products (name, category, description, image, gallery, price, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";

        foreach ($products as $product) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $product['name'],
                $product['category'],
                $product['description'],
                $product['image'],
                json_encode($product['gallery']),
                $product['price'],
                $product['rating']
            ]);
        }

        echo "✓ Products seeded successfully!\n";
    }

    private function seedOrders()
    {
        echo "Seeding orders table...\n";

        $orders = [
            ['user_id' => 1, 'total_price' => 89.99, 'status' => 'completed'],
            ['user_id' => 2, 'total_price' => 199.98, 'status' => 'pending'],
            ['user_id' => 3, 'total_price' => 129.99, 'status' => 'shipped'],
        ];

        $sql = "INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)";

        foreach ($orders as $order) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $order['user_id'],
                $order['total_price'],
                $order['status']
            ]);
        }

        echo "✓ Orders seeded successfully!\n";
    }

    private function seedOrderItems()
    {
        echo "Seeding order items table...\n";

        $orderItems = [
            ['order_id' => 1, 'product_id' => 1, 'product_name' => 'White Shoes', 'product_image' => 'shoe1.png', 'quantity' => 1, 'user_id' => 1],
            ['order_id' => 2, 'product_id' => 2, 'product_name' => 'Black Shoes', 'product_image' => 'shoe2.png', 'quantity' => 1, 'user_id' => 2],
            ['order_id' => 2, 'product_id' => 3, 'product_name' => 'Blue Jacket', 'product_image' => 'jacket1.png', 'quantity' => 1, 'user_id' => 2],
            ['order_id' => 3, 'product_id' => 3, 'product_name' => 'Blue Jacket', 'product_image' => 'jacket1.png', 'quantity' => 1, 'user_id' => 3],
        ];

        $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, user_id, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";

        foreach ($orderItems as $item) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $item['order_id'],
                $item['product_id'],
                $item['product_name'],
                $item['product_image'],
                $item['quantity'],
                $item['user_id']
            ]);
        }

        echo "✓ Order items seeded successfully!\n";
    }

    private function seedPayments()
    {
        echo "Seeding payments table...\n";

        $payments = [
            ['order_id' => 1, 'transaction_id' => 'TXN001'],
            ['order_id' => 2, 'transaction_id' => 'TXN002'],
            ['order_id' => 3, 'transaction_id' => 'TXN003'],
        ];

        $sql = "INSERT INTO payments (order_id, transaction_id) VALUES (?, ?)";

        foreach ($payments as $payment) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $payment['order_id'],
                $payment['transaction_id']
            ]);
        }

        echo "✓ Payments seeded successfully!\n";
    }
}
