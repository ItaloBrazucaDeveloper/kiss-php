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
    $this->setHeaderLocation($url);
  }

  /**
   * Redireciona o usuário para devolta de onde ele veio.
   */
  public function redirectToBack(): void {
    $currentUrl = $_SERVER['REQUEST_URI'];
    $referrer = $_SERVER['HTTP_REFERER'] ?? null;

    $isValidReferrer = $referrer && $referrer !== '' && $currentUrl !== $referrer;
    $previousUrl = $isValidReferrer? $referrer : '/';

    $this->setHeaderLocation($previousUrl);
  }

  private function setHeaderLocation(string $url): void {
    header("Location: {$url}");
    exit();
  }
}
