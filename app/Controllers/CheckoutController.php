<?php
namespace App\Controllers;

use App\Core\Controller;

class CheckoutController extends Controller {
    
    public function index() {
        $this->view('checkout');
    }
}