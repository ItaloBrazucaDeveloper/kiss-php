<?php
namespace App\Services\Auth;

use App\DTOs\LoginUser;

interface IAuthService {
  public function checkCredentials(LoginUser $user): bool;
}