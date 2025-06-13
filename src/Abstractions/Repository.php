<?php
namespace KissPhp\Abstractions;

use \Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use KissPhp\Support\DatabaseParams;

abstract class Repository {
  private static function getConnection(): Connection {
    static $connection;
    return $connection ??= self::createConnection();
  }

  private static function createConnection(): Connection {
    return DriverManager::getConnection(DatabaseParams::getConectionParams());
  }

  /**
   * Fornece acesso ao `EntityManager` do Doctrine para manipulação do banco de dados.
   */
  protected function database(): EntityManagerInterface {
    static $instance;
    return $instance ??= self::createEntityManager();
  }

  private static function createEntityManager(): EntityManager {
   return new EntityManager(
      self::getConnection(),
      ORMSetup::createAttributeMetadataConfiguration(...DatabaseParams::getMatada()));
  }
}