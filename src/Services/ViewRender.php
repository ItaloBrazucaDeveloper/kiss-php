<?php
namespace KissPhp\Services;

use KissPhp\Support\Env;
use KissPhp\Exceptions\NotFound;
use KissPhp\Exceptions\ViewRenderException;
use KissPhp\Config\{ PathsConfig, ViewRenderConfig };

class ViewRender {
  private \Twig\Environment $twig;

  public static function getInstance(): self {
    static $instance;
    return $instance ??= new static();
  }

  private function __construct() {
    try {
      $loader = new \Twig\Loader\FilesystemLoader(rootPath: PathsConfig::VIEWS_PATH);
      $loader->addPath('');
      $loader->addPath(PathsConfig::INFRA_VIEWS_PATH, 'infra');
      foreach (ViewRenderConfig::ALIAS_PATHS as $path => $alias) {
        $loader->addPath($path, $alias);
      }
      $this->twig = new \Twig\Environment($loader, ViewRenderConfig::ENVORIMENT);
    } catch (\Exception $e) {
      throw new ViewRenderException("Failed to initialize Twig environment", 0, $e);
    }

    $this->twig->addFunction(new \Twig\TwigFunction(
      'getInputError',
      function($inputName) {
        $errors = $_SESSION['InputErrors'][$inputName] ?? '';
        unset($_SESSION['InputErrors'][$inputName]);
        return $errors;
      }
    ));

    $isDevMode = Env::get('DEV_MODE') ?? 'false';
    $this->twig->addGlobal('DEV_MODE', strtolower($isDevMode) === 'true');
  }

  public function render(string $viewName, array $params = []): string {
    if (!$this->has($viewName)) throw new NotFound(
      "Cannot found the view: {$viewName}"
    );
    try {
      return $this->twig->render($viewName, $params);
    } catch (\Exception $e) {
      throw new ViewRenderException("Failed to render view: {$viewName}", 0, $e);
    }
  }

  public function has(string $viewName): bool {
    return $this->twig->getLoader()->exists($viewName);
  }
}