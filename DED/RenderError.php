<?php

namespace Infra\DED;

use Infra\Services\View;

class RenderError {
  private static $alreadyRendered = false;

  public static function render(string $errorClass, ?array $data = []): void {
    if (self::$alreadyRendered) return;

    $twig = View::getInstance();
    $viewName = self::getViewNameFromClassName($errorClass);

    $view = $twig->has("@error-page/{$viewName}") ? $viewName : 'default';
    echo $twig->render("@error-page/{$view}", $data);
    self::$alreadyRendered = true;
  }

  private static function getViewNameFromClassName(
    string $classWithNamespace
  ): string {
    $className = preg_replace('#\w+\\\#', '', $classWithNamespace);
    $CamelCaseToKebabCase = preg_replace_callback(
      '#[a-z][A-Z]#',
      fn(array $letters) => $letters[0][0] . '-' . strtolower($letters[0][1]),
      $className
    );
    return strtolower($CamelCaseToKebabCase);
  }
}