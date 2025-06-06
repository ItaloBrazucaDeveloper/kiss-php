<?php
namespace KissPhp\Attributes\Data;

/**
 * Attributo para Data Transfer Object (DTO).
 * 
 * Use este atributo quando quiser trazer os dados do `Body` de uma Request para um objeto, fornecendo tipagem.
 * 
 * Exemplo de uso:
 * ```php
 * #[Post('login')]
 * public function authenticate(#[DTO] User $user)
 * {
 *    // logic of method
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class DTO {
  public function __construct() { }
}