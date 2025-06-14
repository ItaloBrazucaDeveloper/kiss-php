<?php
namespace KissPhp\Services;

use KissPhp\Traits\FlashMessage;

class Session {
  use FlashMessage;

  public function set(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
  }

  public function get(string $key): mixed {
    return $_SESSION[$key] ?? null;
  }

  public function has(string $key): bool {
    return isset($_SESSION[$key]);
  }

  public function remove(string $key): void {
    unset($_SESSION[$key]);
  }

  public function clearAll(): void {
    session_unset();
    session_destroy();
  }
}