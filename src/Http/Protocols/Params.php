<?php
namespace KissPhp\Protocols\Http;

class Params {
  /** @var array<string, string> */
  private array $params = [];

  public function __construct(array $params = []) {
    $this->params = $params;
  }

  public function get(string $key): ?string {
    return $this->params[$key] ?? null;
  }

  public function getAll(): array {
    return $this->params;
  }

  public function has(string $key): bool {
    return isset($this->params[$key]);
  }
}
