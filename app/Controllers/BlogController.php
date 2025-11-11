<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
class BlogController extends Controller {
  public function index(): void { $this->view('blog'); }
}
