<?php

namespace KissPhp\Core\Protocols;

use KissPhp\Core\Types\UploadedFile;

class UploadedFilesCollection {
  /** @var UploadedFile[] */
  private array $files = [];

  public function __construct() {
    if (empty($_FILES)) return;
    $this->files = $this->normalizeFiles($_FILES);
  }

  private function normalizeFiles(array $files): array {
    $normalized = [];

    foreach ($files as $key => $file) {
      if (is_array($file['name'])) {
        // Múltiplos arquivos
        $normalized[$key] = $this->normalizeMultipleFiles($file);
      } else {
        // Arquivo único
        $normalized[$key] = new UploadedFile(
          $file['tmp_name'],
          $file['name'],
          $file['type'],
          $file['size'],
          $file['error']
        );
      }
    }
    return $normalized;
  }

  private function normalizeMultipleFiles(array $file): array {
    $files = [];
    $count = count($file['name']);

    for ($i = 0; $i < $count; $i++) {
      $files[] = new UploadedFile(
        $file['tmp_name'][$i],
        $file['name'][$i],
        $file['type'][$i],
        $file['size'][$i],
        $file['error'][$i]
      );
    }
    return $files;
  }

  public function get(string $key): ?UploadedFile {
    return $this->files[$key] ?? null;
  }

  public function getAll(): array {
    return $this->files;
  }

  public function has(string $key): bool {
    return isset($this->files[$key]);
  }
}
