<?php

namespace Infra\Routing\Collections\interfaces;

use Infra\Routing\Route;

interface IRouteCollection {
  public function add(Route $route): void;
  public function get(string $method, string $endpoint): ?Route;
  public function isEmpty(): bool;
}