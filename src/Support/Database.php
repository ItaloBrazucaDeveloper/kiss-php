<?php
namespace KissPhp\Support;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;

class Database {
  public static function getEntityManager(): EntityManagerInterface {
    static $config;
    static $connection;
    static $entityManager;

    if ($config === null) {
      $config = ORMSetup::createAttributeMetadataConfiguration(
        ...DatabaseParams::getMetadata(),
      );
    }

    if ($connection === null) {
      $connection = DriverManager::getConnection(
        params: DatabaseParams::getConectionParams(),
        config: $config
      );
    }

    if ($entityManager === null) {
      $entityManager = new EntityManager(conn: $connection, config: $config);
    }
    return $entityManager;
  }
}