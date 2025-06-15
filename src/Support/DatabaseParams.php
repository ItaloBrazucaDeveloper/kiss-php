<?php
namespace KissPhp\Support;

use Psr\Cache\CacheItemPoolInterface;

final class DatabaseParams {
  private static array $conectionParams = [
    'dbname' => '',
    'user' => '',
    'password' => '',
    'host' => '',
    'port' => 5432,
    'driver' => 'pdo_pgsql',
    'charset' => 'utf8',
  ];
  
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
    self::$conectionParams = array_merge(self::$conectionParams, $params);
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

  private static array $metadata = [
    'paths' => [],
    'isDevMode' => false,
    'proxyDir' => null,
    'cache' => null,
  ];

  /**
   * Define os matadados para o EntityManager.
   * 
   * @param array{
   *  paths: string[],
   *  isDevMode: bool,
   *  proxyDir: null|string,
   *  cache: null|CacheItemPoolInterface
   * }
   */
  public static function setMetadata(array $metadata): void {
    self::$metadata = array_merge($metadata, self::$metadata);
  }

  /**
   * Retorna os matadados para o EntityManager.
   * 
   * @return array{
   *  paths: string[],
   *  isDevMode: bool,
   *  proxyDir: null|string,
   *  cache: null|CacheItemPoolInterface
   * }
   */
  public static function getMetadata(): array {
    return self::$metadata;
  }
}