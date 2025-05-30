<?php

namespace KissPhp\Routing\Collectors\Interfaces;

interface IControllerCollector {
  public function collect(string $path): array;
}