<?php
namespace KissPhp\Abstractions;

use KissPhp\Services\DataParser;

abstract class Entity {
  /**
   * Converte a entidade para um outro objeto.
   * 
   * @template T
   * 
   * @param class-string<T> $class class-string da classe que deseja fazer a conversão.
   * 
   * @return T
   */
  public function toObject(string $class): object {
    return DataParser::parse($this->toArray(), $class);
  }

  /**
   * Atribue os valores das propriedades de um objeto ao outro.
   * 
   * @template T
   * 
   * @param object<T> $class Objeto que deseja transferir os valores de suas propriedades.
   * 
   * @return T
   */
  public function fromObject(object $class): self {
    $properties = get_object_vars($class);
    
    foreach ($properties as $property => $value) {
      if (property_exists($this, $property)) {
        $this->$property = $value;
      }
    }
    return $this;
  }

  /**
   * Retorna um array com as propriedades e seus valores da entidade.
   * 
   * @return array<string, mixed>
   */
  private function toArray(): array {
    return get_object_vars($this);
  }
}