<?php

namespace Infra\Attributes\Http;

use Infra\Config\View;

#[\Attribute(\Attribute::TARGET_METHOD)]
abstract class HttpRoute {
  public function __construct(
    public readonly string $method,
    public readonly string $path,
    public readonly array $middlewares
  ) { }

  public function getParams(): array {
    $params = [];
    $isValidParams = preg_match_all(View::PARAM_PATTERN, $this->path, $matches);
    
    if (!$isValidParams) return [];
    [, $varNames] = $matches;

    foreach ($varNames as $_ => $varName) $params[$varName] = '';
    return $params;
  }
}