<?php
namespace KissPhp\Attributes\Http\Request;

use KissPhp\Attributes\Data\DataMapping;

/**
 * Atributo para mapear um header da requisição HTTP para um parâmetro do método do controller.
 *
 * Use este atributo quando quiser acessar o valor de um header específico da requisição.
 *
 * Exemplo de uso:
 * ```php
 * public function getUser(#[Header('Authorization')] string $token) {
 *    // lógica do método
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Header extends DataMapping {
  public function __construct(?string $key = null) {
    parent::__construct($key);
  }
}