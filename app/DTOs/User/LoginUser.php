<?php
namespace App\DTOs\User;

use App\Validators\{ Email, Password };

class LoginUser {
  // #[Validate(Email::class)]
  public readonly string $email;

  // #[Validate(Password::class)]
  public readonly string $password;

  public readonly Address $addr;
}
