<?php

namespace KissPhp\Config;

final class RoutingConfig {
  public const ROUTE_PARAM_PATTERN = '#\/:(\w+):(?:{([^}]+)})?(\?|)#';
}