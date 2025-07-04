<?php
namespace KissPhp\Attributes\Http\Request;

/**
 * Atributo para mapear um valor da sessão da requisição HTTP para um parâmetro do método do controller.
 *
 * Use este atributo quando quiser acessar o valor de uma chave da sessão.
 *
 * Exemplo de uso:
 * ```php
 * public function dashboard(#[Session('userId')] int $userId) {
 *    // lógica do método
 * }
 * ```
 */
#[\Deprecated]
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Session extends DataRequestMapping {
  public function getRequestAction(): string {
    return 'getSession';
  }

  public function __construct(?string $key = null) {
    parent::__construct($key);
  }
}