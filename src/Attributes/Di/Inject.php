<?php
namespace KissPhp\Attributes\Di;

/**
 * Atributo para definir o objeto que será injetado em uma propriedade de uma classe.
 * 
 * Use quando uma propriedade, de uma classe, for tipada por uma interface.
 * Nesse caso será preciso informar a classe que será injetada como uma depedência.
 * 
 * Exemplo de uso:
 * ```php
 * public function __construct(
 *    #[Inject(Example::class)] private InterfaceExample $example
 * ) { }
 * ```
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Inject {
  /**
   * @param string $instanceOf `class-string` da classe que deseja injetar.
   */
  public function __construct(
    public readonly string $instanceOf
  ) { }
}