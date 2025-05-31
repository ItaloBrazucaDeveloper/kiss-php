<?php


namespace KissPhp\Core\Routing\Collectors\Interfaces;

use KissPhp\Core\Routing\Collections\Interfaces\IRouteCollection;

interface IRouteCollector {
  public function collect(string $path): IRouteCollection;
}