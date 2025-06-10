<?php
namespace KissPhp\Services;

use KissPhp\Traits\FlashMessage;

class Session {
  use FlashMessage;

  public static function set(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
  }

  public static function get(string $key): mixed {
    return $_SESSION[$key] ?? null;
  }

  public static function has(string $key): bool {
    return isset($_SESSION[$key]);
  }

  public static function remove(string $key): void {
    unset($_SESSION[$key]);
  }

  public static function clearAll(): void {
    session_unset();
    session_destroy();
  }
}