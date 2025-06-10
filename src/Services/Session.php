<?php
namespace KissPhp\Services\Session;

use KissPhp\Traits\FlashMessage;

class SessionService {
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