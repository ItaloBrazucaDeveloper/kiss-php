<?php
namespace KissPhp\Exceptions;

abstract class KissException extends \Exception implements \Throwable {
  public function __construct(
    string $message,
    int $code,
    int $severity,
    string $filename,
    int $lineno,
    string $trace = ''
  ) {
    parent::__construct($message, $code, $severity, $filename, $lineno, $trace);
  }
}