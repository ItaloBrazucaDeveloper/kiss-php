<?php

namespace Infra\Routing\Engine;

use Closure;
use Infra\Http\Request;

class MiddlewarePipeline implements Interfaces\IMiddlewarePipeline {
  public function call($middlewares): Closure {
    return function (Request $request) use ($middlewares): ?Request {
      if (empty($middlewares)) return $request;

      $current = array_shift($middlewares);
      $next = fn($request) => $this->call($middlewares)($request);

      $middleware = new $current();
      return $middleware->handle($request, $next);
    };
  }
}
