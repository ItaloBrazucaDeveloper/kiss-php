<?php
namespace KissPhp\Services;

use KissPhp\Attributes\Di\Inject;
use KissPhp\Exceptions\ContainerException;

class Container {
  private array $instances = [];

  public static function getInstance(): self {
    static $instance;
    return $instance ??= new static();
  }

  /**
   * @template T
   * @param class-string<T> $className
   * @return T
   */
  private function resolve(string $className): mixed {
    try {
      $reflector = new \ReflectionClass($className);
    } catch (\ReflectionException $e) {
      throw new ContainerException("Failed to reflect class: {$className}", 0, $e);
    }
    if (!($constructor = $reflector->getConstructor())) {
      try {
        return $reflector->newInstanceWithoutConstructor();
      } catch (\Exception $e) {
        throw new ContainerException("Failed to instantiate class without constructor: {$className}", 0, $e);
      }
    }

    $parameters = $constructor->getParameters();
    if (count($parameters) <= 0) {
      try {
        return $reflector->newInstance();
      } catch (\Exception $e) {
        throw new ContainerException("Failed to instantiate class: {$className}", 0, $e);
      }
    }

    $args = array_map(function($parameter) {
      $objectToInject = $parameter->getType()->getName();
      $injectAttribute = $parameter->getAttributes(Inject::class);

      if (count($injectAttribute) !== 0) {
        $injectAttributeInstance = $injectAttribute[0]->newInstance();
        $objectToInject = $injectAttributeInstance->instanceOf;
      }
      return $this->get($objectToInject);
    }, $parameters);

    try {
      return $reflector->newInstanceArgs($args);
    } catch (\Exception $e) {
      throw new ContainerException("Failed to instantiate class with dependencies: {$className}", 0, $e);
    }
  }

  /**
   * @template T
   * @param class-string<T> $className
   * @return T
   */
  public function get(string $className): mixed {
    if (!isset($this->instances[$className])) {
      $this->instances[$className] = $this->resolve($className);
    }
    return $this->instances[$className];
  }
}