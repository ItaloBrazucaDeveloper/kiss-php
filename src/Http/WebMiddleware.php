<?php
namespace KissPhp\Http;

use KissPhp\Http\Protocols\Request;

abstract class WebMiddleware {
  /**
   * Função que será chamada antes de cada requisição chegar no controller.
   * 
   * @param Request $request
   * @param Closure $next
   * @return void
   */
  abstract public function handle(Request $request, \Closure $next): ?Request;
}