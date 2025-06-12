<?php
namespace KissPhp\Services;

use KissPhp\Support\Env;
use KissPhp\Exceptions\NotFound;
use KissPhp\Config\{ PathsConfig, ViewRenderConfig };

class ViewRender {
  private \Twig\Environment $twig;

  public static function getInstance(): self {
    static $instance;
    return $instance ??= new static();
  }

  private function __construct() {
    $loader = new \Twig\Loader\FilesystemLoader(rootPath: PathsConfig::VIEWS_PATH);
    $loader->addPath('');
    $loader->addPath(PathsConfig::INFRA_VIEWS_PATH, 'infra');
    
    foreach (ViewRenderConfig::ALIAS_PATHS as $path => $alias) {
      $loader->addPath($path, $alias);
    }
    $this->twig = new \Twig\Environment($loader, ViewRenderConfig::ENVORIMENT);

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
    return $this->twig->render($viewName, $params);
  }

  public function has(string $viewName): bool {
    return $this->twig->getLoader()->exists($viewName);
  }
}