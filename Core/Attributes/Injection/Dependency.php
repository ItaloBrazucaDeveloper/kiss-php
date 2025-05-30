<?php

namespace Infra\Attributes\Injection;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Dependency {
  public function __construct(
    public readonly ?string $instanceOf = null
  ) { }
}