<?php
namespace KissPhp\Traits;

trait FlashMessage {
  private string $key = 'FLASH_MESSAGE_KISS_PHP';

  public function set(string $message): void {
    $_SESSION[$this->key] = $message;
  }

  public function get(): ?string {
    return $_SESSION[$this->key] ?? null;
  }
}