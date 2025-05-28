<?php

namespace Infra\Routing\Collectors;

use Infra\Attributes\Injection\Dependency;

use Infra\Routing\Collections\{
  ControllerCollection,
  Interfaces\IControllerCollection
};

class ControllerCollector implements Interfaces\IControllerCollector {
  #[Dependency(ControllerCollection::class)]
  private IControllerCollection $controllerCollection;

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
    $content = file_get_contents($filePath);

    $hasNamespace = preg_match(
      '/namespace\s+(.+?);/',
      $content,
      $namespaceMatch
    );
    $isWebController = preg_match(
      '/class\s+(\w+)\s+extends\s+WebController/',
      $content,
      $webControllerMatch
    );

    if (!$hasNamespace && !$isWebController) return null;
    return "{$namespaceMatch[1]}\\{$webControllerMatch[1]}";
  }
}