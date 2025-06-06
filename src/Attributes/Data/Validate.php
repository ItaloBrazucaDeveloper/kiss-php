<?php
namespace KissPhp\Attributes\Data;

/**
 * Atributo para definir uma validação em uma propriedade de uma classe.
 * 
 * Use quando quiser atribuir uma validação em uma DTO.
 * 
 * Exemplo de uso:
 * ```php
 * class Address {
 *    #[Validate(ZipCode::class)] 
 *    public string $zipcode;
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Validate {
  public function __construct(
    public readonly string $validator
  ) { }
}