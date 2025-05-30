<?php

namespace Infra\Routing;

class Route {
  public function __construct(
    public readonly string $prefix,
    public readonly string $controller,
    public readonly string $action,
    public readonly string $path,
    public readonly string $method,
    public ?array $middlewares = [],
    public ?array $params = []
  ) { }
}