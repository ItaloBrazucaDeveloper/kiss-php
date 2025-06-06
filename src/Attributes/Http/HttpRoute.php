<?php
namespace KissPhp\Attributes\Http;

use KissPhp\Config\View;

#[\Attribute(\Attribute::TARGET_METHOD)]
abstract class HttpRoute {
  /**
   * @param string $path Rota que deverá ser chamada para fazer uma chamada ao método.
   * @param WebMiddleware[] $middlewares Middlewares que serão chamados antes de executar o método.
   */
  public function __construct(
    public readonly string $method,
    public readonly string $path,
    public readonly array $middlewares
  ) { }

  public function getParams(): array {
    $params = [];
    $isValidParams = preg_match_all(View::PARAM_PATTERN, $this->path, $matches);
    
    if (!$isValidParams) return [];
    [, $varNames] = $matches;

    foreach ($varNames as $_ => $varName) $params[$varName] = '';
    return $params;
  }
}