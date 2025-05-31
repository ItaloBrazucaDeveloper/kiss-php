<?php

namespace KissPhp\Core\Routing\Engine\Interfaces;

use KissPhp\Core\Http\Request;
use KissPhp\Core\Routing\Route;

interface IControllerInvoker {
  public function invoke(Route $route, Request $request): void;
}