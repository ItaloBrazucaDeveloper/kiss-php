<?php
namespace KissPhp\Attributes\Http\Request;

use KissPhp\Attributes\Data\DataMapping;

/**
 * Atributo para mapear um parâmetro da rota (route param) da requisição HTTP para um parâmetro do método do controller.
 *
 * Use este atributo quando quiser acessar o valor de um parâmetro definido na rota.
 *
 * Exemplo de uso:
 * ```php
 * #[Get('/users/{id}')]
 * public function getUser(#[RouteParam('id')] int $id) {
 *    // lógica do método
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class RouteParam extends DataMapping {
  public function __construct(?string $key = null) {
    parent::__construct($key);
  }
}