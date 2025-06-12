<?php
namespace KissPhp\Abstractions;

use KissPhp\Support\Env;

use Doctrine\ORM\ORMSetup;
use \Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

abstract class Repository {
  private static function getConnection(): Connection {
    static $connection;
    return $connection ??= self::createConnection();
  }

  private static function createConnection(): Connection {
    return DriverManager::getConnection([
      'dbname' => Env::get('DB_NAME'),
      'user' => Env::get('DB_USER'),
      'password' => Env::get('DB_PASSWORD'),
      'host' => Env::get('DB_HOST'),
      'port' => (int) Env::get('DB_PORT'),
      'driver' => 'pdo_mysql',
      'charset' => 'utf8mb4',
    ]);
  }

  protected function database(): EntityManager {
    static $instance;
    return $instance ??= self::createEntityManager();
  }

  private static function createEntityManager(): EntityManager {
   return new EntityManager(
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