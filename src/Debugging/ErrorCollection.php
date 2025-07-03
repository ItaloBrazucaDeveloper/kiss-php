<?php
namespace KissPhp\Debugging;

class ErrorCollection {
  private static array $errors = [];

  public static function add(FriendlyError $error): void {
    self::$errors[] = $error;
  }

  public static function getErrors(): array {
    return self::$errors;
  }

  public static function clear(): void {
    self::$errors = [];
  }
}
