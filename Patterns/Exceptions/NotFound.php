<?php

namespace Infra\Exceptions;

use Exception, Throwable;

class NotFound extends Exception implements Throwable {
  public function __construct(
    string $message = "Not Found",
    int $code = 404,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}