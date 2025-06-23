<?php
namespace KissPhp\Protocols\Http;

class Body {
  private array $body;
  private string $rawBody;
  public private(set) string $contentType;

  public function __construct() {
    $this->contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $this->rawBody = file_get_contents('php://input');
    $this->parseBody();
  }

  private function parseBody(): void {
    // Se é multipart/form-data, usa $_POST e $_FILES
    if (strpos($this->contentType, 'multipart/form-data') !== false) {
      $this->body = $_POST;
    }
    // Se é application/x-www-form-urlencoded
    elseif (strpos($this->contentType, 'application/x-www-form-urlencoded') !== false) {
      parse_str($this->rawBody, $this->body);
    }
    // Se é application/json
    elseif (strpos($this->contentType, 'application/json') !== false) {
      $decoded = json_decode($this->rawBody, true);
      $this->body = $decoded ?? [];
    }
    // Para outros tipos, deixa como raw
    else {
      $this->body = ['raw' => $this->rawBody];
    }
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
