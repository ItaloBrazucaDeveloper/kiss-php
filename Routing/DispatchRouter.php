<?php

namespace Infra\Routing;

use Infra\Config\Paths;
use Infra\Http\Request;
use Infra\Exceptions\NotFound;
use Infra\Attributes\Injection\Dependency;

use Infra\Routing\{
  Collectors\RouteCollector,
  Collectors\Interfaces\IRouteCollector,
  Engine\ControllerInvoker,
  Engine\MiddlewarePipeline,
  Engine\Interfaces\IControllerInvoker,
  Engine\Interfaces\IMiddlewarePipeline
};

class DispatchRouter {
  #[Dependency(ControllerInvoker::class)]
  private IControllerInvoker $controllerInvoker;

  #[Dependency(MiddlewarePipeline::class)]
  private IMiddlewarePipeline $middlewareChain;

  #[Dependency(RouteCollector::class)]
  private IRouteCollector $routeCollector;

  public function dispatch(string $method, string $uri): void {
    $route = $this->searchRoute($method, $uri);
    $request = new Request($route);

    $cleanRequest = $this->callMidllewares($route->middlewares, $request);
    if (!$cleanRequest) return;
    
    $this->controllerInvoker->invoke($route, $request);
  }

  private function searchRoute(string $method, string $uri): ?Route {
    $routes = $this->routeCollector->collect(Paths::CONTROLLERS_PATH);
    $route = $routes->get($method, $uri);

    if (!$route) {
      $routeNotFound = $uri === '' ? '/' : $uri;
      throw new NotFound("Route '{$routeNotFound}' not found");
    }
    return $route;
  }

  private function callMidllewares($middlewares, $request): ?Request {
    $chain = $this->middlewareChain->call($middlewares);
    return $chain($request);
  }
}