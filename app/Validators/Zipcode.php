<?php
namespace App\Validators;

use KissPhp\Contracts\IValidator;

class Zipcode implements IValidator {
  public function __construct(
    private string $zipcode
  ) { }

  public function check(): true|string {
    return true;
  }
}