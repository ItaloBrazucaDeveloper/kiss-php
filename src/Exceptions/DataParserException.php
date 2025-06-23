<?php
namespace KissPhp\Exceptions;

class DataParserException extends \Exception implements \Throwable {
  public function __construct(
    string $message = 'Data parse failed!',
    $code = 500,
    ?\Throwable $previous = null
  ) {
    if ($previous !== null) {
      $message .= PHP_EOL . $previous->getMessage();
    }
    parent::__construct($message, $code, $previous);
  }
}