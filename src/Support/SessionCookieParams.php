<?php
namespace KissPhp\Support;

final class SessionCookieParams {
  private static array $sessionCookieParams = [];

  /**
   * Define os parâmetros do cookie de sessão.
   * 
   * @param array{
   *  lifetime: int,
   *  path: string,
   *  domain: string,
   *  secure: bool,
   *  httponly: bool,
   *  samesite: string
   * } $params
   */
  public static function set(array $params): void {
    self::$sessionCookieParams = $params;
  }

  /**
   * Retorna os parâmetros do cookie de sessão.
   * 
   * @return array{
   *  lifetime: int,
   *  path: string,
   *  domain: string,
   *  secure: bool,
   *  httponly: bool,
   *  samesite: string
   * } $params
   */
  public static function get(): array {
    return self::$sessionCookieParams;
  }
}