<?php
namespace Biblionet;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Doctrine {

    public $entityManager;

    function __construct()
    {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $paths = array(APP_DIR."/src/Biblionet/Entities");
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;

        // database configuration parameters
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'host'     => 'db',
            'user'     => 'biblionet',
            'password' => 'biblionet',
            'dbname'   => 'biblionet',
            'charset' => 'UTF8'
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
        $this->entityManager = EntityManager::create($dbParams, $config);
    }

}


?>