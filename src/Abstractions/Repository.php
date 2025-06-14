<?php
namespace KissPhp\Abstractions;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;

use KissPhp\Support\DatabaseParams;

abstract class Repository {
  /**
   * Fornece acesso ao `EntityManager` do Doctrine para manipulação do banco de dados.
   */
  protected function database(): EntityManagerInterface {
    static $connection;
    static $entityManager;

    if ($connection === null) {
      $connection = DriverManager::getConnection(
        DatabaseParams::getConectionParams()
      );
    }

    if ($entityManager === null) {
      $config = ORMSetup::createAttributeMetadataConfiguration(
        ...DatabaseParams::getMetadata(),
      );
      $entityManager = new EntityManager($connection, $config);
    }
    return $entityManager;
  }
}