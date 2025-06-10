<?php
namespace KissPhp\Services;

use KissPhp\Attributes\Di\Inject;

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
    $reflector = new \ReflectionClass($className);
    if (!($constructor = $reflector->getConstructor())) {
      return $reflector->newInstanceWithoutConstructor();
    }

    $parameters = $constructor->getParameters();
    if (count($parameters) <= 0) return $reflector->newInstance();

    $args = array_map(function($parameter) {
      $objectToInject = $parameter->getType()->getName();
      $injectAttribute = $parameter->getAttributes(Inject::class);

      if (count($injectAttribute) !== 0) {
        $injectAttributeInstance = $injectAttribute[0]->newInstance();
        $objectToInject = $injectAttributeInstance->instanceOf;
      }
      return $this->get($objectToInject);
    }, $parameters);

    return $reflector->newInstanceArgs($args);
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