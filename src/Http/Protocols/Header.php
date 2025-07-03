<?php
namespace KissPhp\Protocols\Http;

class Header {
  /** @var array<string, string> */
  private array $headers;

  public function __construct() {
    $this->headers = getallheaders() ?? [];
  }

  public function set(string $key, string $value): void {
    $this->headers[$key] = $value;
  }

  public function get(string $key): ?string {
    return $this->headers[$key] ?? null;
  }

  public function getAll(): array {
    return $this->headers;
  }

  public function has(string $key): bool {
    return isset($this->headers[$key]);
  }

  public function getCookies(): array {
    return $_COOKIE;
  }
}
