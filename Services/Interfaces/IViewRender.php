<?php

namespace Infra\Services\Interfaces;

interface IViewRender {
  public function render(string $view, array $data = []): string;
  public function has(string $view): bool;
}