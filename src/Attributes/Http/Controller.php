<?php

namespace KissPhp\Attributes\Http;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Controller {
  public function __construct(
    public string $prefix = '',
    public array $middlewares = [],
  ) { }
}