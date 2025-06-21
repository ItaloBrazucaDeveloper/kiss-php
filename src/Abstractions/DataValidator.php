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

  /**
   * Retorna o resultado da validação.
   * 
   * @return array{isValid: bool, message: string}
   */
  protected function result(): array {
    error_log(
      $this::class.': isValid: '.boolval($this->isValid).' - message: '.$this->message
    );
    return [
      'isValid' => $this->isValid,
      'message' => $this->message
    ];
  }

  /**
   * Verifica se uma condição tem valor 'true'.
   * 
   * @param bool $condition Condição que será verificada.
   */
  protected function when(bool $condition): self {
    if ($this->isValid && $condition) {
      $this->isValid = false;
    }
    return $this;
  }

  /**
   * Verifica se uma condição tem valor 'false'.
   * 
   * @param bool $condition Condição que será verificada.
   */
  protected function whenNot(bool $condition): self {
    if ($this->isValid && !$condition) {
      $this->isValid = false;
    }
    return $this;
  }

  /**
   * Adiciona a mensagem que deseja notificar caso a validação falhe.
   */
  protected function notify(string $message): self {
    if ($this->isValid === false && $this->message === '') {
      $this->message = $message;
    }
    return $this;
  }
}