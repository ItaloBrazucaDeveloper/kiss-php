<?php
namespace KissPhp\Core\Routing\Engine\Interfaces;

interface IRouteCompiler {
  public function compile(string $routePath): string;
}