<?php
namespace KissPhp\Attributes\Http\Methods;

/**
 * Atributo para definir a `rota` HTTP do método de um controller.
 * 
 * Use quando declarar métodos em controllers.
 * 
 * Exemplo de uso:
 * ```php
 * #[Get('/users')]
 * public function showUsers() {
 *    // logic of method
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Get extends Method {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('GET', $path, $middlewares);
  }
}