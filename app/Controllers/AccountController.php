<?php

namespace App\Controllers;

use App\Core\Controller;

class AccountController extends Controller
{

    public function index()
    {
        $this->view('account');
    }
}
