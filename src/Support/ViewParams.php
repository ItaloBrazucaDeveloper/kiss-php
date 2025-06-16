<?php
namespace KissPhp\Support;

final class ViewParams {
  private static ?array $functions = null;
  private static ?array $globals = null;

  private static function init(): void
  {
    if (self::$functions === null) {
      self::$functions = [
        'getInputError' => function ($inputName) {
          $errors = $_SESSION['InputErrors'][$inputName] ?? '';
          unset($_SESSION['InputErrors'][$inputName]);
          return $errors;
        }
      ];
    }

    if (self::$globals === null) {
      self::$globals = [
        'session' => $_SESSION ?? [],
        'DEV_MODE' => Env::get('DEV_MODE') === 'true',
      ];
    }
  }

  /**
   * @return \Closure[]
   */
  public static function getFunctions(): array {
    self::init();
    return self::$functions;
  }

  /**
   * Adiciona funções que podem ser usadas de qualquer view com $twig.
   * 
   * @param \Closure[] $functions
   */
  public static function addFunctions(array $functions) {
    self::init();
    self::$functions = array_merge(self::$functions, $functions);
  }

  /**
   * @return array<string, mixed>
   */
  public static function getGlobals(): array {
    self::init();
    return self::$globals;
  }

  /**
   * Adiciona valores globais que podem ser acesados de qualquer view com $twig.
   * 
   * @param array<string, mixed> $globals
   */
  public static function addGlobals(array $globals) {
    self::init();
    self::$globals = array_merge(self::$globals, $globals);
  }
}