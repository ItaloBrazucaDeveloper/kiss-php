<?php

namespace Infra\Attributes\Http;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Get extends HttpRoute {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('GET', $path, $middlewares);
  }
}