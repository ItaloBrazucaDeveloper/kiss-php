<?php

namespace KissPhp\Core\Routing\Engine\Interfaces;

use KissPhp\Core\Routing\Route;
use KissPhp\Protocols\Http\Request;

interface IControllerInvoker {
  public function invoke(Route $route, Request $request): void;
}