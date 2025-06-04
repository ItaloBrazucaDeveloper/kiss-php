<?php
namespace App\Services\Auth;

use App\DTOs\LoginUser;

class AuthService implements IAuthService {
  public function __construct(
  ) { }

  public function checkCredentials(LoginUser $user): bool {
    return true;
  }
}