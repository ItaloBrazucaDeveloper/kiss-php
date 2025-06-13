<?php
namespace KissPhp\Abstractions;

use KissPhp\Support\Env;

use \Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

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
      'driver' => 'mysqli',
      'charset' => 'utf8mb4',
    ]);
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
      ORMSetup::createAttributeMetadataConfiguration(
        paths: ['../app/Models'],
        isDevMode: true,
    ));
  }
}