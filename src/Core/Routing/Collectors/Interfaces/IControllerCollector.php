<?php

namespace KissPhp\Core\Routing\Collectors\Interfaces;

interface IControllerCollector {
  public function collect(string $path): array;
}