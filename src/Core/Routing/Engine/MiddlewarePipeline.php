<?php
namespace KissPhp\Core\Routing\Engine;

use KissPhp\Protocols\Http\Request;

class MiddlewarePipeline implements Interfaces\IMiddlewarePipeline {
  public function call($middlewares): \Closure {
    return function (Request $request) use ($middlewares): ?Request {
      if (empty($middlewares)) return $request;

      $current = array_shift($middlewares);
      $next = fn($request) => $this->call($middlewares)($request);

      $middleware = new $current();
      return $middleware->handle($request, $next);
    };
  }
}
