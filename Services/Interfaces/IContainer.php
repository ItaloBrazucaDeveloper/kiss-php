<?php

namespace Infra\Services\Interfaces;

interface IContainer {
  public function get(string $name): mixed;
}