<?php
namespace KissPhp\Attributes\Http;

/**
 * Atributo para definir a `rota` HTTP do método de um controller.
 * 
 * Use quando declarar métodos em controllers.
 * 
 * Exemplo de uso:
 * ```php
 * #[Post('/fruit'), [isAuth::class]]
 * public function createFruit(Request $request) {
 *    // logic of method
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Post extends HttpRoute {
  public function __construct(
    ?string $path = '',
    ?array $middlewares = []
  ) {
    parent::__construct('POST', $path, $middlewares);
  }
}