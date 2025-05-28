<?php

namespace Infra\Routing\Engine\Interfaces;

interface IRouteCompiler {
  public function compile(string $routePath): string;
}