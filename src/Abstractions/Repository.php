<?php
namespace KissPhp\Abstractions;

use KissPhp\Support\Database;
use Doctrine\ORM\EntityManagerInterface;

abstract class Repository {
  /**
   * Fornece acesso ao `EntityManager` do Doctrine para manipulação do banco de dados.
   */
  protected function database(): EntityManagerInterface {
    return Database::getEntityManager();
  }
}