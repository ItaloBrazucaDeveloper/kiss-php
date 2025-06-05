<?php
namespace App\Services\Auth;

use App\DTOs\User\LoginUser;

interface IAuthService {
  public function checkCredentials(LoginUser $user): bool;
}