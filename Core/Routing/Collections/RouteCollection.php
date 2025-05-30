<?php

namespace Infra\Routing\Collections;

use Infra\Routing\Route;
use Infra\Routing\Engine\RouteCompiler;
use Infra\Attributes\Injection\Dependency;

class RouteCollection implements Interfaces\IRouteCollection {
  /** @var array<string, array<string, Route>> */
  private array $routes = [];

  /** @var array<string, array<string, Route>> */
  private array $compiledRoutes = [];

  #[Dependency(RouteCompiler::class)]
  private RouteCompiler $routeCompiler;

  public function add(Route $newRoute): void {
    $endpoint = "{$newRoute->prefix}{$newRoute->path}";
    
    if (!preg_match('/\/:\w+:/', $endpoint)) {
      $this->routes[$newRoute->method][$endpoint] = $newRoute;
      return;
    }
    $endpoint = $this->routeCompiler->compile($endpoint);
    $this->compiledRoutes[$newRoute->method][$endpoint] = $newRoute;
  }

  public function get(string $method, string $endpoint): ?Route {
    if (!isset($this->routes[$method])) return null;

    if (isset($this->routes[$method][$endpoint])) {
      return $this->routes[$method][$endpoint];
    }

    foreach ($this->compiledRoutes[$method] as $routePattern => $route) {
      if (!preg_match($routePattern, $endpoint, $matches)) continue;
      array_shift($matches);

      $namedParams = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
      $route->params = array_replace($route->params, $namedParams);
      return $route;
    }
    return null;
  }

  public function isEmpty(): bool { return empty($this->routes) === 0; }
}