<?php

namespace Biblionet;

use Biblionet\DoctrineBootstrapper;
use Biblionet\ApiManager;

class Biblionet
{
    private $entityManager;
    private $apiManager;
    private $apiFetcher;
    private $dbSaver;

    public function __construct($username, $password)
    {

        $this->entityManager = new DoctrineBootstrapper();
        $this->apiFetcher = new ApiFetcher($username, $password);
        $this->apiManager = new ApiManager($this->entityManager);
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getApiManager()
    {
        return $this->apiManager;
    }

    public function getApiFetcher()
    {
        return $this->apiFetcher;
    }

    public function getDbSDaver()
    {
        return $this->dbSaver;
    }
}
