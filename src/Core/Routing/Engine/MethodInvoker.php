<?php
namespace KissPhp\Core\Routing\Engine;

class MethodInvoker {
  public function invoke(
    \ReflectionMethod $reflectionMethod,
    object $controller,
    array $arguments
  ): void {
    try {
      print_r($reflectionMethod->invokeArgs($controller, $arguments));
    } catch (\Throwable $e) {
      throw new \KissPhp\Exceptions\ControllerInvokeException(
        "Erro ao invocar o método '{$reflectionMethod->getName()}' do controller '" . get_class($controller) . "'.",
        500,
        $e
      );
    }
  }
}