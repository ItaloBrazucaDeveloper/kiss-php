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
      $propertyName = $property->getName();
      
      // Better type checking
      if ($type === null) {
        // No type hint, just assign the raw value
        $value = $data[$propertyName] ?? null;
      } else {
        $value = self::resolvePropertyValue($property, $type, $data);
      }
      
      $property->setValue($instance, $value);
    }
    
    return $instance;
  }

  private static function resolvePropertyValue(\ReflectionProperty $property, \ReflectionType $type, array $data): mixed {
    $propertyName = $property->getName();
    $propertyData = $data[$propertyName] ?? null;
    
    // Handle union types (PHP 8.0+)
    if ($type instanceof \ReflectionUnionType) {
      return self::handleUnionType($property, $type, $data);
    }
    
    // Handle named types
    if ($type instanceof \ReflectionNamedType) {
      return self::handleNamedType($property, $type, $propertyData, $data);
    }
    
    // Fallback to checkValue for other cases
    return self::checkValue($property, $data);
  }
  
  private static function handleUnionType(\ReflectionProperty $property, \ReflectionUnionType $unionType, array $data): mixed {
    $propertyName = $property->getName();
    $propertyData = $data[$propertyName] ?? null;
    
    // Try each type in the union until one works
    foreach ($unionType->getTypes() as $type) {
      if ($type instanceof \ReflectionNamedType) {
        try {
          return self::handleNamedType($property, $type, $propertyData, $data);
        } catch (\Exception $e) {
          // Continue to next type in union
          continue;
        }
      }
    }
    
    // If no type worked, return null if nullable, otherwise throw
    if ($unionType->allowsNull()) {
      return null;
    }
    
    throw new DataParserException("Could not resolve union type for property '{$propertyName}'");
  }
  
  private static function handleNamedType(\ReflectionProperty $property, \ReflectionNamedType $type, mixed $propertyData, array $data): mixed {
    $typeName = $type->getName();
    $propertyName = $property->getName();
    
    // Handle null values
    if ($propertyData === null) {
      return $type->allowsNull() ? null : throw new DataParserException("Property '{$propertyName}' cannot be null");
    }
    
    // Handle array types - check for array of objects pattern
    if ($typeName === 'array' && is_array($propertyData)) {
      return self::handleArrayType($property, $propertyData);
    }
    
    // Handle custom classes (objects)
    if (!$type->isBuiltin() && class_exists($typeName)) {
      return self::handleCustomClass($propertyData, $typeName);
    }
    
    // Handle built-in types with validation
    $value = self::checkValue($property, $data);
    
    // Type casting for built-in types
    if ($value !== null && !$type->isBuiltin()) {
      $expectedType = $typeName;
      $actualType = gettype($value);
      
      if ($expectedType !== $actualType) {
        try {
          $value = TypeCaster::castValue($value, $expectedType);
        } catch (\Exception $e) {
          throw new DataParserException("Failed to cast value for property '{$propertyName}' from {$actualType} to {$expectedType}", 0, $e);
        }
      }
    }
    
    return $value;
  }
  
  private static function handleArrayType(\ReflectionProperty $property, array $propertyData): array {
    $propertyName = $property->getName();
    
    // Check if this is an array of objects by examining the docblock
    $docComment = $property->getDocComment();
    if ($docComment && preg_match('/@var\s+([^\s\[]+)\[\]/', $docComment, $matches)) {
      $elementType = $matches[1];
      if (class_exists($elementType)) {
        // This is an array of objects
        $result = [];
        foreach ($propertyData as $key => $item) {
          if (is_array($item)) {
            $result[$key] = self::parse($item, $elementType);
          } else {
            throw new DataParserException("Expected array of objects for property '{$propertyName}', got non-array item at index {$key}");
          }
        }
        return $result;
      }
    }
    
    // Regular array - return as is
    return $propertyData;
  }
  
  private static function handleCustomClass(mixed $propertyData, string $className): mixed {
    if (is_array($propertyData)) {
      // Single object - parse recursively
      return self::parse($propertyData, $className);
    }
    
    // If it's not an array, try to convert or throw error
    if (is_object($propertyData)) {
      // Convert object to array for parsing
      return self::parse((array) $propertyData, $className);
    }
    
    throw new DataParserException("Expected array or object for custom class '{$className}', got " . gettype($propertyData));
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