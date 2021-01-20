<?php

namespace Biblionet\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends EntityRepository
{

    public function findLatest($number)
    {
        try {
            $dql = "SELECT b, c FROM Biblionet\Entities\Book b JOIN b.category c ORDER BY b.id DESC";

            $query = $this->getEntityManager()->createQuery($dql);
            $query->setMaxResults($number);
            return $query->getResult();
        } catch (QueryException $e) {
            echo $e->getMessage();
        }
    }
}
