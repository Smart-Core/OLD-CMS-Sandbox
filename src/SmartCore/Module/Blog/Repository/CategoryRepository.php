<?php

namespace SmartCore\Module\Blog\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllQuery()
    {
        return $this->_em->createQuery("
          SELECT categories
          FROM {$this->_entityName} AS categories
          ORDER BY categories.id DESC
        ");
    }
}
