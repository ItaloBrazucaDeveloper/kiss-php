<?php

namespace KissPhp\Services;

use KissPhp\Traits\FlashMessage;

class Session {
  use FlashMessage;

  public function set(string $key, mixed $value): void {
    $this->ensureSessionStarted();
    $_SESSION[$key] = $value;
  }

  public function get(string $key, mixed $default = null): mixed {
    $this->ensureSessionStarted();
    return $_SESSION[$key] ?? $default;
  }

  public function has(string $key): bool {
    $this->ensureSessionStarted();
    return isset($_SESSION[$key]);
  }

  public function remove(string $key): void {
    $this->ensureSessionStarted();
    unset($_SESSION[$key]);
  }

  public function clearAll(): void {
    $this->ensureSessionStarted();
    $_SESSION = [];

    // Remove cookie da sessão
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }
    session_destroy();
  }

  public function regenerateId(): void {
    $this->ensureSessionStarted();
    session_regenerate_id(true);
  }

  public function getId(): string {
    $this->ensureSessionStarted();
    return session_id();
  }

  public function all(): array {
    $this->ensureSessionStarted();
    return $_SESSION;
  }

  private function ensureSessionStarted(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }
}
