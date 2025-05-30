<?php

namespace Infra\Http;

class QueryStrings {
  /** @var array<string, string> */
  private array $queryStrings;

  public function __construct() {
    $this->queryStrings = $this->extractQueryStrings();
  }

  public function get(string $key): ?string {
    return $this->queryStrings[$key] ?? null;
  }

  public function has(string $key): bool {
    return isset($this->queryStrings[$key]);
  }

  private function extractQueryStrings(): array {
    $queryString = explode('?', $_SERVER['REQUEST_URI']);
    if (count($queryString) > 1) {
      return explode('&', $queryString[1]);
    }
    return [];
  }
}
