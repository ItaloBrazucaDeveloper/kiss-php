<?php
namespace App\Validators;

use KissPhp\Abstractions\DataValidator;

class Email extends DataValidator {
  public function __construct(private string $email) { }

  public function check(): array {
    return $this->newValidate()
      ->when(empty($this->email))
      ->notify('The field email is required :/')

      ->when(strlen($this->email) < 8)
      ->notify('Email must have at least 8 characters :/')

      ->when(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
      ->notify('Invalid format email :/')
      
      ->result();
  }
}