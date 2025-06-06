<?php
namespace App\Validators;

use KissPhp\Abstractions\DataValidator;

class Password extends DataValidator {
  public function __construct(private string $password) { }

  public function check(): array {
    return $this->newValidate()
      ->when(empty($this->password))
      ->notify('The field passoword is required.')

      ->when(strlen($this->password) < 8)
      ->notify('Password must have at least 8 characters.')
      
      ->whenNot(preg_match('#^[a-zA-Z]+$#', $this->password))
      ->notify('Password must have only letters.')
      
      ->result();
  }
}