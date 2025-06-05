<?php
namespace App\DTOs\User;

use KissPhp\Attributes\Data\Validate;
use App\Validators\{ Email, Password };

class LoginUser {
  #[Validate(Email::class)]
  public readonly string $email;

  #[Validate(Password::class)]
  public readonly string $password;

  public readonly Address $addr;
}
