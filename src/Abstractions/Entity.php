<?php
namespace KissPhp\Abstractions;

use KissPhp\Services\DataParser;

abstract class Entity {
  /**
   * Converte a entidade para um outro objeto.
   * 
   * @template T
   * @param class-string<T> $class
   * @return T
   */
  public function toObject(string $class): object {
    return DataParser::parse($this->toArray(), $class);
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