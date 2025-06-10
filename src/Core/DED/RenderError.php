<?php
namespace KissPhp\Core\DED;

use KissPhp\Services\ViewRender;

class RenderError {
  public static bool $isRendering = false;

  public static function render(string $errorClass, ?array $data = []): void {
    $twig = ViewRender::getInstance();
    $viewName = self::getViewNameFromClassName($errorClass);

    // Mescla os dados passados com os erros acumulados
    $viewData = array_merge($data ?? [], [
      '_errors' => ErrorCollection::getErrors()
    ]);

    $view = $twig->has("@error-page/{$viewName}") ? $viewName : 'default';
    while (ob_get_level()) ob_end_clean();
    echo $twig->render("@error-page/{$view}", $viewData);
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