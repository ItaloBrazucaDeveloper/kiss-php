<?php
namespace KissPhp\Protocols\Http;

class Body {
  private array $body;
  private string $rawBody;
  public private(set) string $contentType;

  public function __construct() {
    $this->contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $this->rawBody = file_get_contents('php://input');
    $this->body = $this->parseBody($this->rawBody);
  }

  private function parseBody($body): array {
    switch ($this->contentType) {
      case 'application/x-www-form-urlencoded':
        parse_str($body, $parsed);
        return $parsed;
      case 'multipart/form-data':
        return $_POST;
      case 'application/json':
        return json_decode($body, true) ?? [];
      default:
        return [];
    };
  }

  public function get(string $key): ?string {
    return $this->body[$key] ?? null;
  }

  public function getAll(): array {
    return $this->body ?? [];
  }

  public function has(string $key): bool {
    return isset($this->body[$key]);
  }
}
