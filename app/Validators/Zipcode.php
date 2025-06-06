<?php
namespace App\Validators;

use KissPhp\Abstractions\DataValidator;

class Zipcode extends DataValidator {
  public function __construct(private string $zipcode) { }

  public function check(): array {
    return $this->newValidate()
      ->when(empty($this->zipcode))
      ->notify('The field zipcode is required :/')

      ->when(strlen($this->zipcode) !== 8)
      ->notify('Zipcode must have at least 8 characteres')

      ->whenNot(preg_match('#^\d+$#', $this->zipcode))
      ->notify('Zipcode must have only numbers')
      
      ->result();
  }
}