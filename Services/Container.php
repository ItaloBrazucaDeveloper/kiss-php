<?php

namespace Infra\Services;

use ReflectionClass;
use Infra\Attributes\Injection\Dependency;

/**
 * @template T
 */
class Container implements Interfaces\IContainer {
  private array $instances = [];

  public static function getInstance(): Interfaces\IContainer {
    static $instance;
    return $instance ??= new static();
  }

  /**
   * @param class-string<T> $className
   * @return T
   */
  private function resolve(string $className): mixed {
    $reflector = new ReflectionClass($className);
    $hasConstructor = !!$reflector->getConstructor();
    
    $instance = $hasConstructor ?
      $reflector->newInstance() :
      $reflector->newInstanceWithoutConstructor();

    $properties = $reflector->getProperties();

    foreach ($properties as $property) {
      $dependecy = $property->getAttributes(Dependency::class);
      
      if (count($dependecy) === 0) continue;
      $dependecyAnnotation = $dependecy[0]->newInstance();

      $dependecyName = $dependecyAnnotation->instanceOf ?? $property->getType()->getName();
      $property->setValue($instance, $this->get($dependecyName));
    }
    return $instance;
  }

  /**
   * @template TClass
   * @param class-string<TClass> $className O nome da classe a ser resolvida.
   * @return TClass
   */
  public function get(string $className): mixed {
    if (!isset($this->instances[$className])) {
      $this->instances[$className] = $this->resolve($className);
    }
    return $this->instances[$className];
  }
}