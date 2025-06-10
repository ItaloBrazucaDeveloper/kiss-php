<?php
namespace KissPhp\Attributes\Data;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class DataMapping {
  /**
   * @param string $key Chave ou nome do valor que será mapeada para uma classe.
   */
  public function __construct(
    public readonly ?string $key = null
  ) { }
}