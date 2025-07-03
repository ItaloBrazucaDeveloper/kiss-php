<?php
namespace KissPhp\Http;

use KissPhp\View\ViewRender;
use KissPhp\Http\Traits\Redirect;

abstract class WebController {
  use Redirect;

  /**
   * Retorna como resposta uma view com os parâmetros fornecidos.
   * 
   * @param string $view Nome da view a ser renderizada.
   * @param array<string, mixed> $data Parâmetros a serem passados para a view.
   */
  public function view(string $view, array $data = []): void {
    // logic of render a view
  }

  /**
   * Retorna como resposta um JSON com os dados fornecidos.
   * @param mixed $data Os dados que deseja enviar.
   */
  public function json(mixed $data = []): void {
    header("Content-Type: application/json");
    echo json_encode($data);
  }
}