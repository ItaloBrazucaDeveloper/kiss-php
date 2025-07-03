<?php
namespace KissPhp\Debugging;

class BoundinaryError {
  public static function register(): void {
    set_exception_handler([self::class, 'handleException']);
    set_error_handler([self::class, 'handleError']);
    register_shutdown_function([self::class, 'handleFatalError']);
    ini_set('display_errors', 'Off');
  }

  public static function handleException(\Throwable $exception): void {
    ErrorCollection::add(new FriendlyError($exception));
    if (RenderError::$isRendering) return;

    RenderError::render($exception::class, [
      '_error' => ErrorCollection::getErrors()
    ]);
    RenderError::$isRendering = true;
    ErrorCollection::clear();
  }

  public static function handleError(
    int $level, string $message, string $file, string $line
  ): void {
    if (error_reporting() & $level) { // Converte erros em exceções
      throw new \ErrorException($message, 0, $level, $file, $line);
    }
  }

  public static function handleFatalError(): void {
    if (!($error = error_get_last())) return;
    $isFatalError = in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR]);

    if ($error !== null && $isFatalError) {
      self::handleException(new \ErrorException(
        $error['message'],
        0,
        $error['type'],
        $error['file'],
        $error['line']
      ));
    }
  }

  public static function wrap(\Closure $closure): void {
    try {
      $closure();
    } catch (\Exception $exception) {
      self::handleException($exception);
    }
  }
}
