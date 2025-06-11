<?php
namespace KissPhp\Support;

use Dotenv\Dotenv;

class Env {
  public static function get(string $key): ?string {
    return $_ENV[$key] ?? null;
  }

  public static function load(string $path): void {
    $dotenv = Dotenv::createImmutable($path);
    $dotenv->safeLoad();
  }
}