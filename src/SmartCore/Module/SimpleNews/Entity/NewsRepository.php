<?php

namespace SmartCore\Module\SimpleNews\Entity;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    /**
     * @param int $count
     * @return News[]
     */
    public function findLastEnabled($count = 5)
    {
        return $this
            ->getFindAllEnablesQuery()
            ->setMaxResults($count)
            ->getResult();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllEnablesQuery()
    {
        return $this
            ->createQueryBuilder('n')
            ->where('n.is_enabled = 1')
            ->orderBy('n.publish_date', 'DESC')
            ->getQuery();
    }
}
