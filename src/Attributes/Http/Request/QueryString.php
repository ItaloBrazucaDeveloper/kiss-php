<?php
namespace KissPhp\Attributes\Http\Request;

use KissPhp\Attributes\Data\DataMapping;

/**
 * Atributo para mapear um parâmetro da query string da requisição HTTP para um parâmetro do método do controller.
 *
 * Use este atributo quando quiser acessar o valor de um parâmetro da query string.
 *
 * Exemplo de uso:
 * ```php
 * public function search(#[QueryString('q')] string $query) {
 *    // lógica do método
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class QueryString extends DataMapping {
  public function __construct(?string $key = null) {
    parent::__construct($key);
  }
}