<?php

namespace App\Database;

use App\Component\Abstract\Singleton;
use App\Component\Config\Config;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

class Database extends Singleton
{
    protected array $options;

    protected EntityManager $entityManager;

    public function __construct()
    {
        $config = Config::getInstance()->setConfigFolder(CONFIG_PATH)->load();

        $databaseConfig = $config->get("database");

        $paths = [$databaseConfig['entities']['path']];
        $cache = new ArrayCache();
        $reader = new AnnotationReader();
        $driver = new AnnotationDriver($reader, $paths);

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($paths, DEBUG_MODE);

        $doctrineConfig->setMetadataCacheImpl($cache);
        $doctrineConfig->setQueryCacheImpl($cache);
        $doctrineConfig->setMetadataDriverImpl($driver);

        $this->options = $databaseConfig['connection'];

        $entityManager = EntityManager::create($this->options, $doctrineConfig);

        $this->setEntityManager($entityManager);
    }

    public function setEntityManager(EntityManager $entityManager): static
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getEntityManger(): EntityManager
    {
        return $this->entityManager;
    }
}