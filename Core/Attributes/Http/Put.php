<?php

namespace Infra\Attributes\Http;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Put extends HttpRoute {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('PUT', $path, $middlewares);
  }
}