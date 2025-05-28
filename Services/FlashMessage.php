<?php

namespace Infra\Services;

use Infra\Http\Session;
use Infra\Attributes\Injection\Dependency;

class FlashMessage {
  #[Dependency(Session::class)]
  private Session $session;

  private string $sessionKey = 'FlashMessage';

  /**
   * Define uma mensagem de flash para a sessão.
   *
   * @param array{type: string, message: string} $flashMessage Array contendo tipo e mensagem de flash
   */
  public function set($flashMessage): void {
    $this->session->set($this->sessionKey, $flashMessage);
  }

  /**
   * Obtém a mensagem de flash da sessão e a remove.
   *
   * @return array{type: string, message: string}|null
   */
  public function get(): ?array {
    if (!$this->has()) return null;
    $message = $this->session->get($this->sessionKey);
    $this->session->remove($this->sessionKey);
    return $message;
  }

  /**
   * Verifica se há uma mensagem de flash na sessão.
   *
   * @return bool Verdadeiro se houver uma mensagem de flash, falso caso contrário.
   */
  public function has(): bool {
    return $this->session->has($this->sessionKey);
  }
  
  /**
   * Limpa a mensagem de flash da sessão.
   */
  public function clear(): void {
    if ($this->session->has($this->sessionKey)) {
      $this->session->remove($this->sessionKey);
    }
  }
}
