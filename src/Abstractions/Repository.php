<?php
namespace KissPhp\Abstractions;

use Doctrine\ORM\ORMSetup;
use \Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use KissPhp\Support\Env;

abstract class Repository {
  private static Connection $connection;
  private static EntityManager $entityManager;

  private static function getConnection(): Connection {
    return $connection ??= DriverManager::getConnection([
      'dbname' => Env::get('DB_NAME'),
      'user' => Env::get('DB_USER'),
      'password' => Env::get('DB_PASSWORD'),
      'host' => Env::get('DB_HOST'),
      'driver' => 'pdo_mysql',
    ]);
  }

  /**
   * Permite a comunicação com o banco de dados.
   */
  protected function database(): EntityManager {
   return $entityManager ??= new EntityManager(
      self::getConnection(),
      ORMSetup::createAttributeMetadataConfiguration(
        paths: ['../app/Models'],
        isDevMode: true,
    ));
  }

  public function __destruct() {
    $this->database()->close();
  }
}