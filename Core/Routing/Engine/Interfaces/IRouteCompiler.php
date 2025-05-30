<?php

namespace KissPhp\Routing\Engine\Interfaces;

interface IRouteCompiler {
  public function compile(string $routePath): string;
}