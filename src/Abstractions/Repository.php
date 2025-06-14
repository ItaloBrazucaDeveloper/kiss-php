<?php
namespace KissPhp\Abstractions;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;

use KissPhp\Support\DatabaseParams;

abstract class Repository {
  /**
   * Fornece acesso ao `EntityManager` do Doctrine para manipulação do banco de dados.
   */
  protected function database(): EntityManagerInterface {
    $connection = DriverManager::getConnection(
      DatabaseParams::getConectionParams(),
      ...DatabaseParams::getMatadata()
    );

    return new EntityManager(
      $connection,
      ...DatabaseParams::getMatadata());
  }
}