<?php

namespace Infra\Services;

use Dotenv\Dotenv as DotEnviroment;

class Dotenv implements Interfaces\IDotenv {
  public static function get(string $key): ?string {
    return $_ENV[$key] ?? null;
  }

  public static function load(string $path = '/'): void {
    $dotenv = DotEnviroment::createImmutable($path);
    $dotenv->safeLoad();
  }
}