<?php
namespace KissPhp\Core\Routing\Engine;

class MethodInvoker implements Interfaces\IMethodInvoker {
  public function invoke(
    object $controller,
    \ReflectionMethod $reflectionMethod,
    array $arguments
  ): void {
    try {
      $positionalArguments = array_values($arguments);
      echo $reflectionMethod->invokeArgs($controller, $positionalArguments);
    } catch (\Throwable $e) {
      $className = get_class($controller);
      $errorMessage = "
        Erro ao invocar o método '{$reflectionMethod->getName()}' do controller {$className}. {$e->getMessage()}
      ";

      throw new \KissPhp\Exceptions\ControllerInvokeException($errorMessage, 500, $e);
    }
  }
}