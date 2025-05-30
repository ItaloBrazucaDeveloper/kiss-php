<?php

namespace KissPhp\Routing\Engine\Interfaces;

use Closure;

interface IMiddlewarePipeline {
  public function call(array $middlewares): Closure;
}