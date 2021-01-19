<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$paths = array(__DIR__."/src/Entities");
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
$entityManager = EntityManager::create($dbParams, $config);
?>