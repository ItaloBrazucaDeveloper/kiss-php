<?php
namespace KissPhp\Services;

use KissPhp\Attributes\Data\Validate;

class DTOParser implements Interfaces\IDTOParser {
  private static array $errors = [];

  public static function getErrors(): array {
    return self::$errors;
  }

  public static function parse(array $data, string $class): mixed {
    $reflector = new \ReflectionClass($class);
    $instance = $reflector->newInstance();
     
    foreach ($reflector->getProperties() as $property) {
      $type = $property->getType();
      $isAObject = !$type->isBuiltin() && class_exists($type->getName());

      ($type && $isAObject)
        ? $value = self::parse((array) $data, $type->getName())
        : $value = self::checkValue($property, $data);

      $property->setValue($instance, $value);
    }
    return $instance;
  }

  private static function checkValue(
    \ReflectionProperty $property,
    mixed $data
  ): mixed {
    $validate = $property->getAttributes(Validate::class);
    if (count($validate) === 0) return $data;

    $name = $property->getName();
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