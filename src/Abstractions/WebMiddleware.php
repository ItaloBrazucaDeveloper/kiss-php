<?php
namespace KissPhp\Abstractions;

use KissPhp\Protocols\Http\Request;

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