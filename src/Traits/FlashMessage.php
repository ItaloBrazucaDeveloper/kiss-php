<?php
namespace KissPhp\Traits;

use KissPhp\Enums\FlashMessageType;

trait FlashMessage {
  private string $key = 'FLASH_MESSAGE_KISS_PHP';

  /**
   * Define uma mensagem flash. Use quando quiser definir mensagens temporárias.
   * 
   * @param FlashMessageType $type O tipo da mensagem flash `('success', 'error', 'warning', 'info')`.
   * @param string $message Mensagem que deseja definir.
   */
  public function setFlashMessage(FlashMessageType $type, string $message): void {
    $_SESSION[$this->key] = [
      'type' => $type->name,
      'message' => $message
    ];
  }

  /**
   * Retorna a mensagem flash definida e a apaga consecutivamente.
   * 
   * @return ?array{
   *  type: string,
   *  message: string
   * }
   */
  public function getFlashMessage(): ?array {
    if (!isset($_SESSION[$this->key])) {
      return null;
    }
    $flash = $_SESSION[$this->key];
    unset($_SESSION[$this->key]);
    return $flash;
  }
}