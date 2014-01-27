<?php

namespace SmartCore\Module\News\Repository;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllQuery()
    {
        return $this
            ->createQueryBuilder('n')
            ->orderBy('n.created', 'DESC')
            ->getQuery();
    }
}
