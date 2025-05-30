<?php

namespace Infra\Routing\Collectors;

use ReflectionClass, ReflectionMethod, ReflectionAttribute;

use Infra\Routing\Route;
use Infra\Attributes\{ Http\HttpRoute, Http\Controller, Injection\Dependency };
use Infra\Routing\Collections\{ RouteCollection, Interfaces\IRouteCollection };

class RouteCollector implements Interfaces\IRouteCollector {
  #[Dependency(ControllerCollector::class)]
  private Interfaces\IControllerCollector $ControllerCollector;

  #[Dependency(RouteCollection::class)]
  private IRouteCollection $routes;

  public function collect(string $path): IRouteCollection {
    $controllers = $this->ControllerCollector->collect($path);

    foreach ($controllers as $controller) {
      $reflectionClass = new ReflectionClass($controller);
      $controllerMethods = $reflectionClass->getMethods();

      array_walk($controllerMethods,
        function(ReflectionMethod $controllerMethod) use ($reflectionClass) {
          $this->setRoutes($reflectionClass, $controllerMethod);
        }
      );
    }
    return $this->routes;
  }

  private function setRoutes(
    ReflectionClass $reflectionClass,
    ReflectionMethod $reflectionMethod
  ): void {
    $controller = $reflectionClass
      ->getAttributes(Controller::class, ReflectionAttribute::IS_INSTANCEOF);
    $httpRoute = $reflectionMethod
      ->getAttributes(HttpRoute::class, ReflectionAttribute::IS_INSTANCEOF);

    if (empty($httpRoute) || empty($controller)) return;
    
    $httpRouteInstance = $httpRoute[0]->newInstance();
    $controllerInstance = $controller[0]->newInstance();

    $middlewares = array_merge(
      $controllerInstance->middlewares,
      $httpRouteInstance->middlewares
    );

    $this->routes->add(new Route(
      $controllerInstance?->prefix,
      $reflectionClass->getName(),
      $reflectionMethod->getName(),
      $httpRouteInstance->path,
      $httpRouteInstance->method,
      $middlewares,
      $httpRouteInstance->getParams(),
    ));
  }
}