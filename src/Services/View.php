<?php
namespace KissPhp\Services;

use KissPhp\Exceptions\NotFound;
use KissPhp\Config\{ Paths, View as ViewConfig };

class View implements Interfaces\IViewRender {
  private \Twig\Environment $twig;

  public static function getInstance(): Interfaces\IViewRender {
    static $instance;
    return $instance ??= new static();
  }

  private function __construct() {
    $loader = new \Twig\Loader\FilesystemLoader();
    $loader->addPath(Paths::VIEWS_PATH);
    $loader->addPath(Paths::INFRA_VIEWS_PATH, 'infra');
    
    foreach (ViewConfig::ALIAS_PATHS as $path => $alias) {
      $loader->addPath($path, $alias);
    }
    
    $this->twig = new \Twig\Environment($loader, ViewConfig::ENVORIMENT);

    $this->twig->addFunction(new \Twig\TwigFunction(
      'getInputError',
      function($inputName) {
        $errors = $_SESSION['InputErrors'][$inputName] ?? '';
        unset($_SESSION['InputErrors'][$inputName]);
        return $errors;
      }
    ));
  }

  public function render(string $viewName, array $params = []): string {
    $resolvedName = $this->resolveViewName($viewName);

    if ($resolvedName === '') throw new NotFound(
      "Cannot found the view: {$resolvedName}"
    );
    return $this->twig->render($resolvedName, $params);
  }

  public function has(string $viewName): bool {
    return $this->resolveViewName($viewName) !== '';
  }

  private function resolveViewName(string $viewName): string {
    foreach (ViewConfig::ACCEPT_EXTENSIONS_FILES as $extension) {
      $fullPath = "{$viewName}{$extension}";
      if ($this->twig->getLoader()->exists($fullPath)) return $fullPath;
    }
    return '';
  }
}