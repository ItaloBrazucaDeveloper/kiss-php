<?php

namespace Infra\Attributes\Http;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Controller {
  public function __construct(
    public string $prefix = '',
    public array $middlewares = [],
  ) { }
}