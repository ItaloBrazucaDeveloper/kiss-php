<?php
namespace KissPhp\Exceptions;

class ControllerInvokeException extends \Exception implements \Throwable {
  public function __construct(
    string $message = "Erro ao invocar o controller ou método.",
    int $code = 500,
    ?\Throwable $previous = null
  ) {
    if ($previous !== null) {
      $message .= PHP_EOL . $previous->getMessage();
    }
    parent::__construct($message, $code, $previous);
  }
}