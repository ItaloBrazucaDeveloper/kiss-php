<?php
namespace KissPhp\Core\Routing\Engine\Interfaces;

interface IMiddlewarePipeline {
  public function call(array $middlewares): \Closure;
}