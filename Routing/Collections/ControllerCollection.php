<?php

namespace Infra\Routing\Collections;

class ControllerCollection implements Interfaces\IControllerCollection {
  /** @var array<string, string> */
  private array $controllers = [];

  public function add(string $controller): void {
    if (!isset($this->controllers[$controller])) {
      $this->controllers[$controller] = $controller;
    }
  }

  public function get(string $controller): ?string {
    return $this->controllers[$controller] ?? null;
  }

  public function isEmpty(): bool { return count($this->controllers) === 0; }

  public function getAll(): array { return $this->controllers; }
}