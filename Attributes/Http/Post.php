<?php

namespace Infra\Attributes\Http;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Post extends HttpRoute {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('POST', $path, $middlewares);
  }
}