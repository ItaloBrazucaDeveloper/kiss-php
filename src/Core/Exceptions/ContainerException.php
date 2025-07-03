<?php
namespace KissPhp\Exceptions;

class ContainerException extends \Exception implements \Throwable {
  public function __construct(
    string $message = 'Container injection failed!',
    $code = 500,
    ?\Throwable $previous = null
  ) {
    if ($previous !== null) {
      $message .= PHP_EOL . $previous->getMessage();
    }
    parent::__construct($message, $code, $previous);
  }
}