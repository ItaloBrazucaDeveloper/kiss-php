<?php

namespace KissPhp\Services\Interfaces;

interface IContainer {
  public function get(string $name): mixed;
}