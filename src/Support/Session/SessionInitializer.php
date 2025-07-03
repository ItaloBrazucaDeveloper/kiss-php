<?php

namespace KissPhp\Support\Session;

use KissPhp\Support\Session\SessionCookieParams;

class SessionInitializer {
  public static function init(): void {
    // Configurações de segurança antes de iniciar
    self::setSecuritySettings();

    if (count(SessionCookieParams::get()) > 0) {
      session_set_cookie_params(SessionCookieParams::get());
    }

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
      // Regenera ID na primeira inicialização também
      session_regenerate_id(true);
    } else if (session_status() === PHP_SESSION_ACTIVE) {
      session_regenerate_id(true);
    }
  }

  private static function setSecuritySettings(): void {
    // Previne ataques XSS
    ini_set('session.cookie_httponly', 1);

    // Força HTTPS em produção
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      ini_set('session.cookie_secure', 1);
    }

    // Previne ataques de fixação
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);
  }
}
