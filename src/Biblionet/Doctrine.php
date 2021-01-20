<?php
namespace Biblionet;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;

class Doctrine {

    public $entityManager;

    function __construct()
    {
        $applicationMode = "development";
        
        $paths = array(APP_DIR."/src/Biblionet/Entities");

        $useSimpleAnnotationReader = false;

        $cache = new \Doctrine\Common\Cache\ArrayCache;

        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($paths, $useSimpleAnnotationReader);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(APP_DIR."/src/Biblionet/Proxies");
        $config->setProxyNamespace("Biblionet\Proxies\\");
        
        if ($applicationMode == "development") {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        // database configuration parameters
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'host'     => 'db',
            'user'     => 'biblionet',
            'password' => 'biblionet',
            'dbname'   => 'biblionet',
            'charset' => 'UTF8'
        );

        $this->entityManager = EntityManager::create($dbParams, $config);
    }

}


?>