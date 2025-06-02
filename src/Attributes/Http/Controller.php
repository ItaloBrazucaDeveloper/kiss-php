<?php

namespace KissPhp\Attributes\Http;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Controller {
  public function __construct(
    public private(set) string $prefix = '',
    public readonly array $middlewares = [],
  ) {
    if ($this->prefix === 'index') $this->prefix = '';
  }
}