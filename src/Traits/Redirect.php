<?php
namespace KissPhp\Traits;


trait Redirect {
  /**
   * Redireciona o usuário para a URL fornecida.
   * 
   * @param string $url URL para a qual o usuário será redirecionado.
   */
  public function redirectTo(string $url): void {
    if ($url === '') {
      throw new \InvalidArgumentException("[redirect function] URL cannot be empty");
    }

    $url = filter_var($url, FILTER_SANITIZE_URL);
    if (!$url) {
      throw new \InvalidArgumentException("[redirect function] Invalid URL: {$url}");
    }
    header("Location: {$url}");
    exit;
  }

  /**
   * Redireciona o usuário para devolta de onde ele veio.
   */
  public function redirectToBack(): void {
    if (!isset($_SERVER['HTTP_REFERER'])) {
      throw new \InvalidArgumentException("[redirect function] Cannot redirect to back without HTTP_REFERER");
    }
    $url = $_SERVER['HTTP_REFERER'] ?? '/';

    unset($_SERVER['HTTP_REFERER']);
    header("Location: {$url}");
    exit;
  }
}
