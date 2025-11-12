<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers;
use App\Models\UserModel;
use App\Models\CartModel;

class RegisterController extends Controller
{
    private UserModel $users;
    public function __construct(UserModel $users) { $this->users = $users; }
    public function index(): void
    {
        if (session_status() === \PHP_SESSION_NONE) { @session_start(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim((string)$_POST['name']) : '';
            $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
            $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
            $confirm = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';
            $exists = $email !== '' ? $this->users->findByEmail($email) : null;
            if (!$exists && $name !== '' && $email !== '' && $password !== '' && $password === $confirm) {
                $user = $this->users->create($name, $email, $password);
                if ($user) {
                    $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
                    CartModel::mergeSessionIntoUserCart();
                    $to = (string)($_POST['redirect'] ?? ($_GET['redirect'] ?? ''));
                    $dest = $to !== '' ? $to : Helpers::base_url('index.php?page=account');
                    header('Location: ' . $dest);
                    exit;
                }
            }
        }
        $this->view('register');
    }
}
