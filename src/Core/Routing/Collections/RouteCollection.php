<?php
namespace KissPhp\Core\Routing\Collections;

use KissPhp\Core\Routing\Route;
use KissPhp\Attributes\Di\Inject;
use KissPhp\Core\Routing\Engine\RouteCompiler;

class RouteCollection implements Interfaces\IRouteCollection {
  /** @var array<string, array<string, Route>> */
  private array $routes = [];

  /** @var array<string, array<string, Route>> */
  private array $compiledRoutes = [];

  public function __construct(
    #[Inject(RouteCompiler::class)] private RouteCompiler $routeCompiler
  ) { }

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
    if (isset($this->routes[$method][$endpoint])) {
      return $this->routes[$method][$endpoint];
    }
    if (!isset($this->compiledRoutes[$method])) return null;

    foreach ($this->compiledRoutes[$method] as $routePattern => $route) {
      if (!preg_match($routePattern, $endpoint, $matches)) continue;
      array_shift($matches);

      $namedParams = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
      $route->params = array_replace($route->params, $namedParams);
      return $route;
    }
    return null;
  }

  public function isEmpty(): bool {
    return count($this->routes) === 0;
  }
}