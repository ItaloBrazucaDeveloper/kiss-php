<?php

namespace App\Controllers;

use KissPhp\Protocols\Http\Request;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\{ Controller, Get };

#[Controller('index')]
class AuthController extends WebController {
  #[Get('/login')]
  public function showLoginPage(Request $request) {
    return 'Login Page';
  }
}