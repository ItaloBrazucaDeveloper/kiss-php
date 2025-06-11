<?php
namespace KissPhp\Support;

class SessionInitializer {
  public static function init(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_set_cookie_params(SessionCookieParams::get());
      session_start();
    } else if (session_status() === PHP_SESSION_ACTIVE) {
      session_regenerate_id(true);
    }
  }
}