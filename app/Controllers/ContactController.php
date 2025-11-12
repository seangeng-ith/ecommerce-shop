<?php

namespace App\Controllers;

use App\Core\Controller;

class ContactController extends Controller {

    public function index() {
        $sent = false;
        $errors = [];
        $data = [];
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            $data = [
                'name' => trim((string)($_POST['name'] ?? '')),
                'email' => trim((string)($_POST['email'] ?? '')),
                'profession' => trim((string)($_POST['profession'] ?? '')),
                'subject' => trim((string)($_POST['subject'] ?? '')),
                'message' => trim((string)($_POST['message'] ?? '')),
            ];
            if ($data['name'] === '' || $data['email'] === '' || $data['message'] === '') {
                $errors[] = 'Please fill all required fields.';
            } else {
                $sent = true;
            }
        }
        $this->view('contact', ['sent' => $sent, 'errors' => $errors, 'data' => $data]);
    }
}
