<?php

namespace Infra\Config;

final class View {
  public const PARAM_PATTERN = '/\/:(\w+):(?:{([^}]+)})?(\?|)/';

  public const ACCEPT_EXTENSIONS_FILES = [
    '.html.twig',
    '.twig',
    '.html',
    '.php'
  ];

  public const ENVORIMENT = [
    // 'cache' => '/path/to/compilation_cache',
    'debug' => true,
    'auto_reload' => true,
  ];

  public const ALIAS_PATHS = [
    Paths::VIEWS_PATH . 'Pages/[errors]/' => 'error-page',
    Paths::INFRA_VIEWS_PATH => 'Infra',
  ];
}