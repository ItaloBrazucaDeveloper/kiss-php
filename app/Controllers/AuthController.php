<?php
namespace App\Controllers;

use App\DTOs\User\LoginUser;
use App\Services\Auth\{ AuthService, IAuthService };

use KissPhp\Attributes\Data\DTO;
use KissPhp\Attributes\Di\Inject;
use KissPhp\Abstractions\WebController;
use KissPhp\Attributes\Http\{ Controller, Get, Post };

#[Controller('index')]
class AuthController extends WebController {
  public function __construct(
    #[Inject(AuthService::class)] private IAuthService $service
  ) { }

  #[Get('/login')]
  public function showLoginPage() {
    $this->render('Pages/login/page');
  }

  #[Post('/login')]
  public function login(#[DTO] LoginUser $user) {
    echo '<pre>';
    print_r($user);
    // $isValidUser = $this->service->checkCredentials($user);
    // return boolval($isValidUser);
  }
}