<?php

namespace App\DTOs\User;

class Address {
  // #[Validate(Email::class)]
  public readonly string $zipcode;
}