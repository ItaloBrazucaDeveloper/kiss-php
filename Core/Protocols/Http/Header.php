<?php

namespace Infra\Http;

class Header {
  /** @var array<string, string> */
  private array $headers;

  public function __construct() {
    $this->headers = getallheaders() ?? [];
  }

  public function get(string $key): ?string {
    return $this->headers[$key] ?? null;
  }

  public function has(string $key): bool {
    return isset($this->headers[$key]);
  }

  public function getCookies(): array {
    return $_COOKIE;
  }
}
