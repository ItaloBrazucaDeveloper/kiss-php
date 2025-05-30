<?php

namespace Infra\Http;

class Body {
  public private(set) ?string $contentType;
  private array $body;

  public function __construct() {
    $this->contentType = $_SERVER['CONTENT_TYPE'] ?? null;
    $this->body = $this->parseBody(file_get_contents('php://input'));
  }

  private function parseBody($body): array {
    switch ($this->contentType) {
      case 'application/x-www-form-urlencoded':
      case 'multipart/form-data':
        parse_str($body, $parsed);
        return $parsed;
      case 'application/json':
        return json_decode($body, true) ?? [];
      default:
        return [];
    };
  }

  public function get(string $key): ?string {
    return $this->body[$key] ?? null;
  }

  public function has(string $key): bool {
    return isset($this->body[$key]);
  }
}
