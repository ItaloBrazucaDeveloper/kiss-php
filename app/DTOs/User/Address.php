<?php
namespace App\DTOs\User;

use App\Validators\Zipcode;
use KissPhp\Attributes\Data\Validate;

class Address {
  #[Validate(Zipcode::class)]
  public readonly string $zipcode;
}