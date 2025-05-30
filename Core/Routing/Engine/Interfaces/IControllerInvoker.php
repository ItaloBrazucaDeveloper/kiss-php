<?php

namespace KissPhp\Routing\Engine\Interfaces;

use KissPhp\Http\Request;
use KissPhp\Routing\Route;

interface IControllerInvoker {
  public function invoke(Route $route, Request $request): void;
}