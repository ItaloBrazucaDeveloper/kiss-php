<?php

namespace KissPhp\Core\Routing\Collections\interfaces;

use KissPhp\Core\Routing\Route;

interface IRouteCollection {
  public function add(Route $route): void;
  public function get(string $method, string $endpoint): ?Route;
  public function isEmpty(): bool;
}