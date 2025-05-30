<?php

namespace Infra\Routing\Engine;

use ReflectionMethod;

use Infra\Http\Request;
use Infra\Routing\Route;
use Infra\Services\Container;

class ControllerInvoker implements Interfaces\IControllerInvoker {
  public function invoke(Route $route, Request $request): void {
    $controller = $route->controller;
    $container = Container::getInstance();
    $controllerWithDependencies = $container->get($controller);
    
    $action = $route->action;
    $reflectionMethod = new ReflectionMethod(
      $controllerWithDependencies,
      $action
    );
    $arguments = $this->resolveMethodParameters($reflectionMethod, $request);
    echo $reflectionMethod->invokeArgs($controllerWithDependencies, $arguments);
  }

  private function resolveMethodParameters(
    ReflectionMethod $reflectionMethod,
    Request $request
  ): array {
    $arguments = [];
    $methodParameters = $reflectionMethod->getParameters();

    foreach ($methodParameters as $parameter) {
      if ($parameter->getType() && $parameter->getType()->getName() === Request::class) {
        $arguments[] = $request; // Adiciona o objeto Request se for declarado
      }
    }
    return $arguments;
  }
}