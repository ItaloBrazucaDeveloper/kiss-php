<?php
namespace KissPhp\Attributes\Http\Methods;

/**
 * Atributo para definir a `rota` HTTP do método de um controller.
 * 
 * Use quando declarar métodos em controllers.
 * 
 * Exemplo de uso:
 * ```php
 * #[Delete('/:userID:{numeric}')]
 * public function byebyeUser(Request $request) {
 *    // logic of method
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Delete extends Method {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('DELETE', $path, $middlewares);
  }
}