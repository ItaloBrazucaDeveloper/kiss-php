<?php

namespace KissPhp\Core\Routing\Engine;

use KissPhp\Attributes\Data\DTO;
use KissPhp\Core\Routing\Route;
use KissPhp\Services\Container;
use KissPhp\Protocols\Http\Request;
use KissPhp\Services\DTOParser;

class ControllerInvoker implements Interfaces\IControllerInvoker {
  public function invoke(Route $route, Request $request): void {
    $controller = $route->controller;
    $container = Container::getInstance();
    $controllerWithDependencies = $container->get($controller);
    
    $action = $route->action;
    $reflectionMethod = new \ReflectionMethod(
      $controllerWithDependencies,
      $action
    );
    $arguments = $this->resolveMethodParameters($reflectionMethod, $request);
    echo $reflectionMethod->invokeArgs($controllerWithDependencies, $arguments);
  }

  private function resolveMethodParameters(
    \ReflectionMethod $reflectionMethod,
    Request $request
  ): array {
    $arguments = [];
    $methodParameters = $reflectionMethod->getParameters();

    foreach ($methodParameters as $parameter) {
      if (!($parameterType = $parameter->getType())) continue;

      if ($parameterType->getName() === Request::class) {
        $arguments[] = $request;
      } else if ($parameter->getAttributes(DTO::class)[0]) {
        $arguments[] = DTOParser::parse(
          $request->body->getAll(),
          $parameterType->getName()
        );
      }
    }
    return $arguments;
  }
}