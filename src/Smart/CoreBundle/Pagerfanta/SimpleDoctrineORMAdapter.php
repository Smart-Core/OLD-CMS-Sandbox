<?php

namespace Smart\CoreBundle\Pagerfanta;

use Doctrine\ORM\Query;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Adapter\DoctrineORM\Paginator as LegacyPaginator;

/**
 * Copy of \Pagerfanta\Adapter\DoctrineORMAdapter with force LegacyPaginator
 */
class SimpleDoctrineORMAdapter implements AdapterInterface
{
    /**
     * @var \Pagerfanta\Adapter\DoctrineORM\Paginator
     */
    protected $paginator;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query A Doctrine ORM query or query builder.
     * @param Boolean $fetchJoinCollection Whether the query joins a collection (true by default).
     */
    public function __construct($query, $fetchJoinCollection = false)
    {
        $this->paginator = new LegacyPaginator($query, $fetchJoinCollection);
    }

    /**
     * Returns the query
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->paginator->getQuery();
    }

    /**
     * Returns whether the query joins a collection.
     *
     * @return Boolean Whether the query joins a collection.
     */
    public function getFetchJoinCollection()
    {
        return $this->paginator->getFetchJoinCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return count($this->paginator);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $this->paginator
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($length);

        return $this->paginator->getIterator();
    }
}
