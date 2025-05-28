<?php

namespace Infra\DED;

use Throwable;

class FriendlyError {
  public readonly string $title;
  public readonly int $code;
  public readonly string $message;
  public readonly array $trace;
  public readonly array $where;
  public readonly array $snippet;

  public function __construct(Throwable $exception) {
    $this->title = self::formatTitle($exception::class);
    $this->code = $exception->getCode();
    $this->message = $exception->getMessage();
    $this->trace = $exception->getTrace();
    $this->where = [
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
    ];
    $this->snippet = self::getSnippet(
      $exception->getFile(),
      $exception->getLine()
    );
  }

  private static function getSnippet(
    string $file, int $errorLine, int $context = 3
  ): array {
    $lines = file($file);
    $start = max($errorLine - $context - 2, 0);
    $end = min($errorLine + $context - 1, count($lines) - 1);

    $length = $end - $start + 1;
    $extractedLines = array_slice($lines, $start, $length);

    return array_map(
      function ($line, $index) use ($errorLine, $start) {
        if (empty($line)) return null;
        return [
          'number' => $index + 1 + $start,
          'text' => rtrim($line),
          'isError' => $index + 1 + $start === $errorLine
        ];
      },
      array_values($extractedLines),
      array_keys($extractedLines)
    );
  }

  private static function formatTitle(string $classWithNamespace): string {
    $className = basename(str_replace('\\', '/', $classWithNamespace));
    return preg_replace('/(?<!^)([A-Z])/', ' $1', $className);
  } 
}
