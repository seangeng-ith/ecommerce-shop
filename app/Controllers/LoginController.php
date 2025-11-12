<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers;
use App\Models\UserModel;
use App\Models\CartModel;

class LoginController extends Controller {
    private UserModel $users;
    public function __construct(UserModel $users) { $this->users = $users; }
    public function index(): void {
        if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
            $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
            $user = ($email !== '' && $password !== '') ? $this->users->verify($email, $password) : null;
            if ($user) {
                $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
                CartModel::mergeSessionIntoUserCart();
                $to = (string)($_POST['redirect'] ?? ($_GET['redirect'] ?? ''));
                $dest = $to !== '' ? $to : Helpers::base_url('index.php?page=account');
                header('Location: ' . $dest);
                exit;
            }
        }
        $this->view('login');
    }
    public function forgot(): void {
        if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
        $sent = false;
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
            if ($email !== '') {
                $sent = true;
            }
        }
        $this->view('forgot', ['sent' => $sent]);
    }
    public function logout(): void {
        unset($_SESSION['user']);
        unset($_SESSION['cart_order_id']);
        $_SESSION['cart'] = [];
        header('Location: ' . Helpers::base_url('index.php?page=login'));
        exit;
    }
}