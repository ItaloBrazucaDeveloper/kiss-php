<?php

namespace KissPhp\Config;

final class RoutingConfig {
  public const string ROUTE_PARAM_PATTERN = '#\/:(\w+):(?:{([^}]+)})?(\?|)#';
}