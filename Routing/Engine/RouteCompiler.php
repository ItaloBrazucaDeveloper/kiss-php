<?php

namespace Infra\Routing\Engine;

use Infra\Config\View;

class RouteCompiler implements Interfaces\IRouteCompiler {
  public function compile(string $routePath): string {
    // Converte path param para um grupo de captura nomeado. Ex: (/|/(?P<param>[^/]+))
    $routePatternCompiled = preg_replace_callback(
      View::PARAM_PATTERN,
      function($matches): string {
        [, $paramName, $validationType, $isOptional] = $matches;
        
        $validationTypePattern = $this->getTypePatternOfRoute($validationType);
        return "(\/|\/(?P<{$paramName}>{$validationTypePattern}))"
                . ($isOptional ? '?' : '');
      }, $routePath);

    return "#^{$routePatternCompiled}$#";
  }

  private function getTypePatternOfRoute(string $validationType): string {
    return match ($validationType) {
      'numeric' => '\d+',
      'alpha' => '[a-zA-Z]+',
      'alphanum' => '[a-zA-Z0-9]+',
      default => $validationType,
    };
  }
}