<?php
namespace KissPhp\Core\Routing\Collectors;

use KissPhp\Attributes\Di\Inject;

use KissPhp\Core\Routing\Collections\{
  ControllerCollection,
  Interfaces\IControllerCollection
};

class ControllerCollector implements Interfaces\IControllerCollector {
  public function __construct(
    #[Inject(ControllerCollection::class)]
      private IControllerCollection $controllerCollection
  ) { }

  public function collect(string $controllersPath): array {
    foreach (scandir($controllersPath) as $file) {
      if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

      $fullPathController = "{$controllersPath}/{$file}";
      $controller = $this->getClassNameFromFile($fullPathController);

      if (!$controller && $this->controllerCollection->get($controller)) {
        continue;
      }
      $this->controllerCollection->add($controller);
    }
    return $this->controllerCollection->getAll();
  }

  private function getClassNameFromFile(string $filePath): ?string {
    $content = @file_get_contents($filePath);
    if ($content === false) {
      throw new \KissPhp\Exceptions\ControllerCollectorException(
        "Não foi possível ler o arquivo do controller: {$filePath}"
      );
    }
    
    $hasNamespace = preg_match(
      '/namespace\\s+(.+?);/',
      $content,
      $namespaceMatch
    );
    $isWebController = preg_match(
      '/class\\s+(\\w+)\\s+extends\\s+WebController/',
      $content,
      $webControllerMatch
    );
    if (!$hasNamespace || !$isWebController) {
      throw new \KissPhp\Exceptions\ControllerCollectorException(
        "Arquivo de controller inválido ou sem namespace/classe esperada: {$filePath}"
      );
    }
    return "{$namespaceMatch[1]}\\{$webControllerMatch[1]}";
  }
}