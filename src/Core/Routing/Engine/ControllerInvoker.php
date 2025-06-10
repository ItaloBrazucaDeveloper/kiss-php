<?php
namespace KissPhp\Core\Routing\Engine;

use KissPhp\Core\Routing\Route;
use KissPhp\Protocols\Http\Request;
use KissPhp\Attributes\Data\DataMapping;
use KissPhp\Services\{ Container, DataParser, Session };

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
      } else if ($parameter->getAttributes(DataMapping::class, \ReflectionAttribute::IS_INSTANCEOF)) {
        $arguments[] = DataParser::parse(
          $request->body->getAll(),
          $parameterType->getName()
        );

        if (count(DataParser::getErrors()) > 0) {
          Session::set('InputErrors', DataParser::getErrors());
          $referer = $_SERVER['HTTP_REFERER'] ?? '/';
          header("Location: {$referer}");
          exit;
        }
      }
    }
    return $arguments;
  }
}