<?php
require_once "vendor/autoload.php";

define("APP_DIR", __DIR__);

use Biblionet\Doctrine;

$doctrine = new Doctrine;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($doctrine->entityManager);
?>