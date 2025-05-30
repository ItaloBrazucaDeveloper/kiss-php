<?php


namespace Infra\Routing\Collectors\Interfaces;

use Infra\Routing\Collections\Interfaces\IRouteCollection;

interface IRouteCollector {
  public function collect(string $path): IRouteCollection;
}