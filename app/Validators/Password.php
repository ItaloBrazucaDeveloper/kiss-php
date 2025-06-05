<?php
namespace App\Validators;

use KissPhp\Contracts\IValidator;

class Password implements IValidator {
  public function __construct(
    private string $password
  ) { }

  public function check(): true|string {
    return false;
  }
}