<?php
namespace KissPhp\Abstractions;

abstract class DataValidator {
  private bool $isValid;
  private string $message;

  /**
   * Função que será chamada para validação.
   * 
   * @return array{isValid: bool, message: string}
   */
  abstract protected function check(): array;

  protected function newValidate(): self {
    $this->isValid = true;
    $this->message = '';
    return $this;
  }

  protected function result(): array {
    error_log(
      $this::class.': isValid: '.boolval($this->isValid).' - message: '.$this->message
    );
    return [
      'isValid' => $this->isValid,
      'message' => $this->message
    ];
  }

  protected function when(bool $condition): self {
    if ($this->isValid && $condition) {
      $this->isValid = false;
    }
    return $this;
  }

  protected function whenNot(bool $condition): self {
    if ($this->isValid && !$condition) {
      $this->isValid = false;
    }
    return $this;
  }

  protected function notify(string $message): self {
    if ($this->isValid === false && $this->message === '') {
      $this->message = $message;
    }
    return $this;
  }
}