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
        $this->seedUsers();
        $this->seedProducts();
        $this->seedOrders();
        $this->seedOrderItems();
        $this->seedPayments();
    }

    private function seedUsers()
    {
        echo "Seeding users table...\n";

        $users = [
            ['John Doe', 'john@email.com', 'password123', '0912345678', 'New York', '123 Main St'],
            ['Jane Smith', 'jane@email.com', 'password123', '0987654321', 'Los Angeles', '456 Oak Ave'],
            ['Mike Johnson', 'mike@email.com', 'password123', '0923456789', 'Chicago', '789 Pine Rd'],
        ];

        $sql = "INSERT INTO users (user_name, user_email, user_password, phone, city, address) VALUES (?, ?, ?, ?, ?, ?)";

        foreach ($users as $user) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $user[0],
                $user[1],
                password_hash($user[2], PASSWORD_BCRYPT),
                $user[3],
                $user[4],
                $user[5]
            ]);
        }

        echo "✓ Users seeded successfully!\n";
    }

    private function seedProducts()
    {
        echo "Seeding products table...\n";

        $products = [
            [
                'name' => 'White Shoes',
                'category' => 'Shoes',
                'description' => 'Comfortable white athletic shoes',
                'image' => 'shoe1.png',
                'image2' => 'shoe1.png',
                'image3' => 'shoe1.png',
                'image4' => 'shoe1.png',
                'price' => 89.99,
                'special_offer' => 0,
                'color' => 'White'
            ],
            [
                'name' => 'Black Shoes',
                'category' => 'Shoes',
                'description' => 'Classic black leather shoes',
                'image' => 'shoe2.png',
                'image2' => 'shoe2.png',
                'image3' => 'shoe2.png',
                'image4' => 'shoe2.png',
                'price' => 99.99,
                'special_offer' => 1,
                'color' => 'Black'
            ],
            [
                'name' => 'Blue Jacket',
                'category' => 'Clothes',
                'description' => 'Warm winter blue jacket',
                'image' => 'jacket1.png',
                'image2' => 'jacket1.png',
                'image3' => 'jacket1.png',
                'image4' => 'jacket1.png',
                'price' => 129.99,
                'special_offer' => 0,
                'color' => 'Blue'
            ],
            [
                'name' => 'Red Bag',
                'category' => 'Bags',
                'description' => 'Stylish red shoulder bag',
                'image' => 'bag1.png',
                'image2' => 'bag1.png',
                'image3' => 'bag1.png',
                'image4' => 'bag1.png',
                'price' => 69.99,
                'special_offer' => 0,
                'color' => 'Red'
            ],
            [
                'name' => 'Black T-Shirt',
                'category' => 'Clothes',
                'description' => 'Comfortable black cotton t-shirt',
                'image' => 'tshirt1.png',
                'image2' => 'tshirt1.png',
                'image3' => 'tshirt1.png',
                'image4' => 'tshirt1.png',
                'price' => 29.99,
                'special_offer' => 0,
                'color' => 'Black'
            ],
        ];

        $sql = "INSERT INTO products (product_name, product_category, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_special_offer, product_color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        foreach ($products as $product) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $product['name'],
                $product['category'],
                $product['description'],
                $product['image'],
                $product['image2'],
                $product['image3'],
                $product['image4'],
                $product['price'],
                $product['special_offer'],
                $product['color']
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
