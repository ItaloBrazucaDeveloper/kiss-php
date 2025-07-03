<?php
namespace KissPhp\Exceptions;

class ViewRenderException extends \Exception implements \Throwable {
  public function __construct(
    string $message = 'Failed to render view',
    $code = 500,
    ?\Throwable $previous = null
  ) {
    if ($previous !== null) {
      $message .= PHP_EOL . $previous->getMessage();
    }
    parent::__construct($message, $code, $previous);
  }
}