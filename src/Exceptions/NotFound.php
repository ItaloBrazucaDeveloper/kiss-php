<?php
namespace KissPhp\Exceptions;

class NotFound extends \Exception implements \Throwable {
  public function __construct(
    string $message = "Not Found",
    int $code = 404,
    ?\Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}