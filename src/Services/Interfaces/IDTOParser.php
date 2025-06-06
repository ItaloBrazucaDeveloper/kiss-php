<?php
namespace KissPhp\Services\Interfaces;

interface IDTOParser {
  public static function parse(array $data, string $class): mixed;
  public static function getErrors(): array;
}