<?php
namespace KissPhp\Support\Data;

class TypeCaster {
  private static array $castMap = [
    'int' => 'intval',
    'integer' => 'intval',
    'float' => 'floatval',
    'double' => 'floatval',
    'bool' => 'self::boolCast',
    'boolean' => 'self::boolCast',
    'string' => 'strval',
    'array' => 'self::arrayCast',
    'object' => 'self::objectCast',
    'null' => 'self::nullCast',
  ];

  public static function castValue($value, string $type) {
    if (!isset(self::$castMap[$type])) {
      throw new \InvalidArgumentException("Tipo '$type' não suportado.");
    }
    $callback = self::$castMap[$type];
    return is_callable($callback) ? call_user_func($callback, $value) : $value;
  }

  private static function boolCast($value) {
    return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
  }

  private static function arrayCast($value) {
    return is_array($value) ? $value : [$value];
  }

  private static function objectCast($value) {
    return is_array($value) ? (object) $value : (object) [$value];
  }

  private static function nullCast($value) {
    return null;
  }
}
