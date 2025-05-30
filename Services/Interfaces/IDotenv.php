<?php

namespace KissPhp\Services\Interfaces;

interface IDotenv {
  public static function get(string $key): ?string;
  public static function load(): void;
}