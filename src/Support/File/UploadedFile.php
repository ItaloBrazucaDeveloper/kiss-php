<?php
namespace KissPhp\Support\File;

class UploadedFile {
  public function __construct(
    private string $tempName,
    private string $name,
    private string $type,
    private int $size,
    private int $error
  ) { }

  /**
   * Verifica se o upload foi válido
   */
  public function isValid(): bool {
    return $this->error === UPLOAD_ERR_OK && is_uploaded_file($this->tempName);
  }

  /**
   * Move o arquivo para um destino
   */
  public function move(string $destination): bool {
    if (!$this->isValid()) {
      return false;
    }

    return move_uploaded_file($this->tempName, $destination);
  }

  /**
   * Obtém o conteúdo do arquivo
   */
  public function getContent(): string {
    if (!$this->isValid()) return '';
    return file_get_contents($this->tempName);
  }

  /**
   * Obtém informações do arquivo
   */
  public function getName(): string {
    return $this->name;
  }

  public function getType(): string {
    return $this->type;
  }

  public function getSize(): int {
    return $this->size;
  }

  public function getError(): int {
    return $this->error;
  }

  public function getTempName(): string {
    return $this->tempName;
  }

  /**
   * Obtém a extensão do arquivo
   */
  public function getExtension(): string {
    return pathinfo($this->name, PATHINFO_EXTENSION);
  }

  /**
   * Verifica se é uma imagem
   */
  public function isImage(): bool {
    $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    return in_array($this->type, $imageTypes);
  }
}
