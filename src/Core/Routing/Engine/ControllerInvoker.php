<?php
namespace KissPhp\Core\Routing\Engine;

use KissPhp\Core\Routing\Route;
use KissPhp\Services\Container;
use KissPhp\Attributes\Di\Inject;
use KissPhp\Protocols\Http\Request;

class ControllerInvoker implements Interfaces\IControllerInvoker {
  public function __construct(
    #[Inject(MethodInvoker::class)]
    private Interfaces\IMethodInvoker $methodInvoker,

    #[Inject(ParameterResolver::class)]
    private Interfaces\IParameterResolver $parameterResolver
  ) {  }

  public function invoke(Route $route, Request $request): void {
    $controller = $route->controller;
    $container = Container::getInstance();
    try {
      $controllerWithDependencies = $container->get($controller);
    } catch (\Throwable $e) {
      throw new \KissPhp\Exceptions\ControllerInvokeException(
        "Não foi possível instanciar o controller '{$controller}'.",
        500,
        $e
      );
    }

    try {
      $reflectionMethod = \ReflectionMethod::createFromMethodName(
        "{$route->controller}::{$route->action}"
      );
    } catch (\ReflectionException $e) {
      throw new \KissPhp\Exceptions\ControllerInvokeException(
        "O método '{$route->action}' não existe no controller '{$controller}'.",
        500,
        $e
      );
    }
    $arguments = $this->parameterResolver->resolveParameters($reflectionMethod, $request);
    $this->methodInvoker->invoke($controllerWithDependencies, $reflectionMethod, $arguments);
  }
}