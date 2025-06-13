<?php
namespace KissPhp\Protocols\Http;

class QueryStrings {
  /** @var array<string, string> */
  private array $queryStrings;

  public function __construct() {
    $this->queryStrings = $this->extractQueryStrings();
  }

  public function get(string $key): ?string {
    return $this->queryStrings[$key] ?? null;
  }

  public function getAll(): array {
    return $this->queryStrings;
  }

  public function has(string $key): bool {
    return isset($this->queryStrings[$key]);
  }

  private function extractQueryStrings(): array {
    $queryString = $_SERVER['QUERY_STRING'];
    parse_str($queryString, $params);
    return $params;
  }
}
