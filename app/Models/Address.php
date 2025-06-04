<?php
namespace App\Models;

class Address {
  public function __construct(
    public ?string $city,
    public ?string $state,
    public ?string $street,
    public ?string $zipCode,
    public ?string $neighborhood
  ) { }
}