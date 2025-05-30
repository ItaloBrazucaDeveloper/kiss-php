<?php


namespace KissPhp\Routing\Collectors\Interfaces;

use KissPhp\Routing\Collections\Interfaces\IRouteCollection;

interface IRouteCollector {
  public function collect(string $path): IRouteCollection;
}