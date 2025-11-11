<?php

namespace App\Controllers;

use App\Core\Controller;

class RegisterController extends Controller
{

    public function index()
    {
        $this->view('register');
    }
}
