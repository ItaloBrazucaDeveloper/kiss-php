<?php
namespace KissPhp\Attributes\Data;

/**
 * Atributo para mapear o corpo (body) da requisição HTTP para um parâmetro do método do controller.
 *
 * Use este atributo quando quiser acessar um arquivo enviado pela requisição diretamente em um parâmetro do método.
 *
 * Exemplo de uso:
 * ```php
 * public function createUser(#[File] UploadedFile $avatar) {
 *    // lógica do método
 * }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class File {
  /**
   * @param ?string $key A chave do arquivo no corpo da requisição.
   * @param ?array $allowedTypes Tipos MIME permitidos para o arquivo
   */
  public function __construct(
    public ?string $key = null,
    public ?array $allowedTypes = null
  ) { }
}
