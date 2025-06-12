<?php
namespace KissPhp\Abstractions;

use KissPhp\Services\ViewRender;
use KissPhp\Traits\Redirect;

abstract class WebController {
  use Redirect;

  /**
   * Renderiza uma view com os parâmetros fornecidos.
   * 
   * @param string $view Nome da view a ser renderizada.
   * @param array<string, mixed> $data Parâmetros a serem passados para a view.
   * @return string O conteúdo renderizado da view.
   */
  public function render(string $view, array $data = []): void {
    echo ViewRender::getInstance()->render($view, $data);
    exit;
  }
}