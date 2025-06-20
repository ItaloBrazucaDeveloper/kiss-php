<?php
namespace KissPhp\Core\Routing;

use KissPhp\Config\PathsConfig;
use KissPhp\Exceptions\NotFound;
use KissPhp\Protocols\Http\Request;
use KissPhp\Attributes\Di\Inject;

use KissPhp\Core\Routing\{
  Collectors\RouteCollector,
  Collectors\Interfaces\IRouteCollector,
  Engine\ControllerInvoker,
  Engine\MiddlewarePipeline,
  Engine\Interfaces\IControllerInvoker,
  Engine\Interfaces\IMiddlewarePipeline
};

class DispatchRouter {
  public function __construct(
    #[Inject(ControllerInvoker::class)]
      private IControllerInvoker $controllerInvoker,
      
    #[Inject(MiddlewarePipeline::class)]
      private IMiddlewarePipeline $middlewareChain,

    #[Inject(RouteCollector::class)]
      private IRouteCollector $routeCollector,
  ) { }

  public function dispatch(string $method, string $uri): void {
    $route = $this->searchRoute($method, $uri);
    $request = new Request($route);

    $cleanRequest = $this->callMidllewares($route->middlewares, $request);
    if (!$cleanRequest) {
      throw new \KissPhp\Exceptions\MiddlewareException(
        "A requisição foi interrompida por um middleware."
      );
    }
    $this->controllerInvoker->invoke($route, $request);
  }

  private function searchRoute(string $method, string $uri): ?Route {
    $routes = $this->routeCollector->collect(PathsConfig::CONTROLLERS_PATH);
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