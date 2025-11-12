<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
class UserModel {
  public function findByEmail(string $email): ?array {
    try {
      $db = Database::getInstance();
      $row = $db->fetch('SELECT id,name,email,password_hash FROM users WHERE email = ? LIMIT 1', [$email]);
      return $row ?: null;
    } catch (\Throwable $e) {
      return null;
    }
  }
  public function create(string $name, string $email, string $password): ?array {
    try {
      $db = Database::getInstance();
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $db->query('INSERT INTO users (name,email,password_hash,created_at) VALUES (?,?,?,NOW())', [$name,$email,$hash]);
      return $this->findByEmail($email);
    } catch (\Throwable $e) {
      return null;
    }
  }
  public function verify(string $email, string $password): ?array {
    $user = $this->findByEmail($email);
    if (!$user) return null;
    if (!isset($user['password_hash']) || !password_verify($password, (string)$user['password_hash'])) return null;
    return $user;
  }
  public function changePassword(string $email, string $newPassword): bool {
    try {
      $db = Database::getInstance();
      $hash = password_hash($newPassword, PASSWORD_DEFAULT);
      $db->query('UPDATE users SET password_hash = ? WHERE email = ?', [$hash, $email]);
      return true;
    } catch (\Throwable $e) {
      return false;
    }
  }
}