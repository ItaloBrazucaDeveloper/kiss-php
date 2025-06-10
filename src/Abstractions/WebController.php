<?php
namespace KissPhp\Abstractions;

use KissPhp\Services\ViewRenderService;
use KissPhp\Services\Session;

abstract class WebController {
  public function __construct(private Session $session) { }

  /**
   * Redireciona o usuário para a URL fornecida.
   * 
   * @param string $url URL para a qual o usuário será redirecionado.
   */
  public function redirect(string $url): void {
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
    echo ViewRenderService::getInstance()->render($view, $data);
    exit;
  }
}