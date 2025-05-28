<?php

namespace Infra\Attributes\Http;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Delete extends HttpRoute {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('DELETE', $path, $middlewares);
  }
}