<?php
namespace KissPhp\Exceptions;

class ControllerCollectorException extends \Exception implements \Throwable {
    public function __construct(
        string $message = "Erro ao coletar controllers.",
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}