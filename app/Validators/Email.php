<?php

namespace App\Validators;

use KissPhp\Contracts\IValidator;

class Email implements IValidator {
  public function __construct(
    private string $email
  ) { }

  public function check(): bool {
    return false;
  }
}