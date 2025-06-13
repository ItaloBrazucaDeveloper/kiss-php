<?php
namespace KissPhp\Attributes\Http\Request;

/**
 * Atributo para mapear o corpo (body) da requisição HTTP para um parâmetro do método do controller.
 *
 * Use este atributo quando quiser acessar o conteúdo do body da requisição diretamente em um parâmetro do método.
 *
 * Exemplo de uso:
 * ```php
 * public function createUser(#[Body] Body $data) {
 *    // lógica do método
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Body extends DataRequestMapping {
  public function getRequestAction(): string {
    return 'getAllBody';
  }

  public function __construct(?string $key = null) {
    parent::__construct($key);
  }
}