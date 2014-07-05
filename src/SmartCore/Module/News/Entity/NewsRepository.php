<?php

namespace SmartCore\Module\News\Entity;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
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
