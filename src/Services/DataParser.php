<?php
namespace KissPhp\Services;

use KissPhp\Attributes\Data\Validate;
use KissPhp\Support\TypeCaster;
use KissPhp\Exceptions\DataParserException;

class DataParser {
  private static array $errors = [];
  
  public static function getErrors(): array { return self::$errors; }
  
  public static function clearErrors(): void { self::$errors = []; }

  public static function parse(array $data, string $class): mixed {
    // Clear previous errors
    self::clearErrors();
    
    try {
      $reflector = new \ReflectionClass($class);
      $instance = $reflector->newInstance();
    } catch (\ReflectionException $e) {
      throw new DataParserException(message: "Failed to reflect or instantiate class: {$class}", previous: $e);
    }
     
    foreach ($reflector->getProperties() as $property) {
      $type = $property->getType();
      
      // Better type checking
      if ($type === null) {
        // No type hint, just assign the raw value
        $value = $data[$property->getName()] ?? null;
      } elseif (!$type->isBuiltin() && class_exists($type->getName())) {
        // It's a custom class - recursively parse
        $propertyData = isset($data[$property->getName()]) ? (array) $data[$property->getName()] : [];
        $value = self::parse($propertyData, $type->getName());
      } else {
        // Built-in type or has validation
        $value = self::checkValue($property, $data);
        
        // Type casting for built-in types
        if ($value !== null && $type !== null) {
          $expectedType = $type->getName();
          $actualType = gettype($value);
          
          if ($expectedType !== $actualType) {
            try {
              $value = TypeCaster::castValue($value, $expectedType);
            } catch (\Exception $e) {
              throw new DataParserException("Failed to cast value for property '{$property->getName()}' from {$actualType} to {$expectedType} in class '{$class}'", 0, $e);
            }
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
    $type = $property->getType();
    $allowNull = $type?->allowsNull() ?? true;
    
    // Check if the key exists in data
    $hasValue = array_key_exists($name, $data);
    $value = $hasValue ? $data[$name] : null;

    // If no validation attributes, return the value or null based on type
    if (count($validate) === 0) {
      if (!$hasValue) {
        return $allowNull ? null : throw new DataParserException("Required property '{$name}' is missing");
      }
      return $value;
    }

    // If value is null and type allows null, return null
    if ($value === null && $allowNull) {
      return null;
    }
    
    // If value is null but type doesn't allow null, it's an error
    if ($value === null && !$allowNull) {
      self::$errors[$name] = "Property '{$name}' cannot be null";
      throw new DataParserException("Property '{$name}' cannot be null");
    }

    // Run validation
    $validateAttribute = $validate[0]->newInstance();
    $validationResult = self::callValidatorOfProperty($validateAttribute, $value);

    if (!$validationResult['isValid']) {
      self::$errors[$name] = $validationResult['message'];
      
      // If validation fails and null is allowed, return null
      if ($allowNull) {
        return null;
      }
      
      // If validation fails and null is not allowed, throw exception
      throw new DataParserException("Validation failed for property '{$name}': {$validationResult['message']}");
    }
    
    return $value;
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