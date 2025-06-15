<?php
namespace KissPhp\Support;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\ProxyFactory;

class Database {
  public static function getEntityManager(): EntityManagerInterface {
    static $config;
    static $connection;
    static $entityManager;

    if ($config === null) {
      $metadata = DatabaseParams::getMetadata();
      $config = ORMSetup::createAttributeMetadataConfiguration(
        $metadata['paths'],
        $metadata['isDevMode']
      );

      $strategyOfAutoGenerateProxyClasses = $metadata['isDevMode']
        ? ProxyFactory::AUTOGENERATE_ALWAYS
        : ProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS;

      $config->setAutoGenerateProxyClasses($strategyOfAutoGenerateProxyClasses);
    }

    if ($connection === null) {
      $connection = DriverManager::getConnection(
        DatabaseParams::getConectionParams(),
        $config
      );
    }

    if ($entityManager === null) {
      $entityManager = new EntityManager($connection, $config);
    }
    return $entityManager;
  }
}