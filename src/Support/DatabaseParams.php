<?php
namespace KissPhp\Support;

use Psr\Cache\CacheItemPoolInterface;

final class DatabaseParams {
  private static array $conectionParams = [];
  
  /**
   * Define os parâmetros do cookie de sessão.
   * 
   * @param array{
   *  'dbname': string,
   *  'user': string,
   *  'password': string,
   *  'host': string,
   *  'port': int,
   *  'driver': string,
   *  'charset': string
   * } $params
   */
  public static function setConnectionParams(array $params): void {
    self::$conectionParams = $params;
  }
  
  /**
   * Retorna os parâmetros do cookie de sessão.
   * 
   * @return array{
   *  'dbname': string,
   *  'user': string,
   *  'password': string,
   *  'host': string,
   *  'port': int,
   *  'driver': string,
   *  'charset': string
   * } $params
   */
  public static function getConectionParams(): array {
    return self::$conectionParams;
  }

  private static array $metadata = [];

  /**
   * Define os matadados para o EntityManager.
   * 
   * @param array{
   *  paths: string[],
   *  isDevMode: bool,
   *  proxyDir: ?string,
   *  cache: ?CacheItemPoolInterface
   * }
   */
  public static function setMetadata(array $metadata): void {
    self::$metadata = $metadata;
  }

  /**
   * Retorna os matadados para o EntityManager.
   * 
   * @return array{
   *  paths: string[],
   *  isDevMode: bool,
   *  proxyDir: ?string,
   *  cache: ?CacheItemPoolInterface
   * }
   */
  public static function getMatadata(): array {
    return self::$metadata;
  }
}