<?php
namespace KissPhp\Attributes\Http\Methods;

/**
 * Atributo para definir a `rota` HTTP do método de um controller.
 * 
 * Use quando declarar métodos em controllers.
 * 
 * Exemplo de uso:
 * ```php
 * #[Put('/:commentID:{numeric}')]
 * public function refreshComment(Request $request) {
 *    // logic of method
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Put extends Method {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('PUT', $path, $middlewares);
  }
}