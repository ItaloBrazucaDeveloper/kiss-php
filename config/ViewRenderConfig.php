<?php
namespace KissPhp\Config;

final class ViewRenderConfig {
  public const array ENVORIMENT = [
    // 'cache' => '/path/to/compilation_cache',
    'debug' => true,
    'auto_reload' => true,
  ];

  public const array ALIAS_PATHS = [
    'Pages/[errors]/' => 'error-page',
    PathsConfig::INFRA_VIEWS_PATH => 'Infra',
  ];
}