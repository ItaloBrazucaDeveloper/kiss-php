<?php

namespace KissPhp\Services;

class DTOParser {
  public static function parse(array $data, string $class): mixed {
    $reflector = new \ReflectionClass($class);
    $properties = $reflector->getProperties();

    $instance = $reflector->newInstance();
     
    foreach ($properties as $property) {
      if (!$data[$property->getName()]) continue;

      $value = $data[$property->getName()];
      $property->setValue($value);
    }
    return $instance;
  }
}