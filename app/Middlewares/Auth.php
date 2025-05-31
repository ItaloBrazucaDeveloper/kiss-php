<?php

namespace App\Middlewares;

use Closure;
use KissPhp\Http\Request;
use KissPhp\Abstractions\WebMiddleware;

class Auth extends WebMiddleware {
  public function handle(Request $request, Closure $next): ?Request {
    return $next($request);
  }
}