<?php
namespace KissPhp\Services;

use KissPhp\Attributes\Data\Validate;
use KissPhp\Support\TypeCaster;
use KissPhp\Exceptions\DataParserException;

class DataParser {
  private static array $errors = [];
  
  public static function getErrors(): array { return self::$errors; }

  public static function parse(array $data, string $class): mixed {
    try {
      $reflector = new \ReflectionClass($class);
      $instance = $reflector->newInstance();
    } catch (\ReflectionException $e) {
      throw new DataParserException(message: "Failed to reflect or instantiate class: {$class}", previous: $e);
    }
     
    foreach ($reflector->getProperties() as $property) {
      $type = $property->getType();
      $isAObject = !$type->isBuiltin() && class_exists($type->getName());

      if ($type && $isAObject) {
        $propertyData = isset($data[$property->getName()]) ? (array) $data[$property->getName()] : $data;
        $value = self::parse($propertyData, $type->getName());
      } else {
        $value = self::checkValue($property, $data);
        
        if ($value !== null && $type->getName() !== gettype($value)) {
          try {
            $value = TypeCaster::castValue($value, $type->getName());
          } catch (\Exception $e) {
            throw new DataParserException("Failed to cast value for property '{$property->getName()}' in class '{$class}'", 0, $e);
          }
        }
      }
      $property->setValue($instance, $value);
    }
    return $instance;
  }

  private static function checkValue(
    \ReflectionProperty $property,
    mixed $data
  ): mixed {
    $validate = $property->getAttributes(Validate::class);
    $name = $property->getName();

    if (count($validate) === 0) return $data[$name] ?? null;
    $allowNull = $property->getType()->allowsNull();

    $validateAttribute = $validate[0]->newInstance();
    $validate = self::callValidatorOfProperty($validateAttribute, $data[$name]);

    if (!$validate['isValid'] && $allowNull) {
      return null;
    } else if (!$validate['isValid'] && !$allowNull) {
      self::$errors[$property->getName()] = $validate['message'];
    }
    return $data[$name];
  }

  /**
   * @return array{isValid: bool, message: string}
   */
  public static function callValidatorOfProperty(
    Validate $validateAttribute,
    mixed $value
  ): array {
    $validator = $validateAttribute->validator;
    return new $validator($value)->check();
  }
}