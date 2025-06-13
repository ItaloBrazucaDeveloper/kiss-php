<?php
namespace KissPhp\Exceptions;

class MiddlewareException extends \Exception implements \Throwable {
    public function __construct(
        string $message = "Erro ao processar o middleware.",
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}