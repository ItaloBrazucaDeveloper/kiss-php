<?php

namespace Infra\Abstractions;

use Infra\Attributes\Injection\Dependency;
use Infra\Services\{ View, FlashMessage };

abstract class WebController {
  #[Dependency(FlashMessage::class)]
  protected FlashMessage $flashMessage;
  
  /**
   * Redireciona o usuário para a URL fornecida.
   * 
   * @param string $url URL para a qual o usuário será redirecionado.
   * @param array{type: string, message: string}|null $flashMessage Array contendo tipo e mensagem de flash
   *        onde type pode ser 'success', 'error', 'warning' ou 'info'
   */
  public function redirect(
    string $url,
    ?array $flashMessage = null,
  ): void {
    if ($flashMessage) $this->flashMessage->set($flashMessage);
    header("Location: {$url}");
    exit;
  }

  /**
   * Renderiza uma view com os parâmetros fornecidos.
   * 
   * @param string $view Nome da view a ser renderizada.
   * @param array<string, mixed> $data Parâmetros a serem passados para a view.
   * @return string O conteúdo renderizado da view.
   */
  public function render(string $view, array $data = []): void {
    echo View::getInstance()->render($view, $data);
    exit;
  }
}