<?php

namespace KissPhp\Config;

final class ViewRenderConfig {
  public const ENVORIMENT = [
    // 'cache' => '/path/to/compilation_cache',
    'debug' => true,
    'auto_reload' => true,
  ];

  public const ALIAS_PATHS = [
    PathsConfig::VIEWS_PATH . 'Pages/[errors]/' => 'error-page',
    PathsConfig::INFRA_VIEWS_PATH => 'Infra',
  ];
}