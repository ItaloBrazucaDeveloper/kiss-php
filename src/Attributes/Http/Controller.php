<?php
namespace KissPhp\Attributes\Http;

/**
 * Atributo para definir um `controller` na aplicação.
 * 
 * Exemplo de uso:
 * ```php
 * #[Controller('/example', [ExampleMiddleware::class])]
 * class ExempleController extends WebController { }
 * ```
 * @property 'index'|string $prefix Prefixo das rotas declaradas nos métodos do controller.
 * Defina o prefixo como `index` e o controller será invocado quando a rota for uma barra (`/`).
 * @property WebMiddleware[] $middlewares Middlewares que serão chamados antes de invocar um controller.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Controller {
  public function __construct(
    public private(set) string $prefix,
    public readonly array $middlewares = [],
  ) {
    if ($this->prefix === 'index') $this->prefix = '';
  }
}