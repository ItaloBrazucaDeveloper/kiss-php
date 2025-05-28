<?php

namespace Infra\Routing\Engine\Interfaces;

use Infra\Http\Request;
use Infra\Routing\Route;

interface IControllerInvoker {
  public function invoke(Route $route, Request $request): void;
}