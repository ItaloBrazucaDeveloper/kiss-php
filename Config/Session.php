<?php

namespace Infra\Config;

final class Session {
  public static function getSessionCookieParams(): array {
    return [
      'lifetime' => 3600,           // 1 hora
      'path' => '/',                // caminho do cookie 
      'domain' => '',               // domínio atual
      'secure' => false,            // apenas HTTPS
      'httponly' => true,           // previne acesso via JavaScript
      'samesite' => 'Strict'        // proteção contra CSRF
    ];
  }

  public static function init(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_set_cookie_params(self::getSessionCookieParams());
      session_start();
    } else if (session_status() === PHP_SESSION_ACTIVE) {
      session_regenerate_id(true);
    }
  }
}