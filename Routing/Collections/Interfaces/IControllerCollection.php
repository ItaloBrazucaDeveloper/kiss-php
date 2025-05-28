<?php

namespace Infra\Routing\Collections\interfaces;

interface IControllerCollection {
  public function add(string $controller): void;
  public function getAll(): ?array;
  public function get(string $controller): ?string;
  public function isEmpty(): bool;
}