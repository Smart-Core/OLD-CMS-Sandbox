<?php

namespace SmartCore\Module\Blog\Repository;

use Doctrine\ORM\EntityRepository;
use SmartCore\Module\Blog\Model\TagInterface;

class TagRepository extends EntityRepository
{
    /**
     * @param TagInterface $tag
     *
     * @return int
     *
     * @todo возможность выбора по нескольким тэгам.
     */
    public function getArticlesCountByTag(TagInterface $tag)
    {
        $query = $this->_em->createQuery("
            SELECT COUNT(a.id)
            FROM {$this->_entityName} AS t
            JOIN t.articles AS a
            WHERE t = :tag
            AND a.is_enabled = true
        ")->setParameter('tag', $tag);

        return $query->getSingleScalarResult();
    }

    /**
     * @param TagInterface $tag
     *
     * @return int
     *
     * @todo возможность выбора по нескольким тэгам.
     * @todo убрать в репу TagRepository
     */
    public function getCountByTag(TagInterface $tag)
    {
        $query = $this->_em->createQuery("
            SELECT COUNT(a.id)
            FROM {$this->_entityName} AS t
            JOIN t.articles AS a
            WHERE t = :tag
            AND a.is_enabled = true
        ")->setParameter('tag', $tag);

        return $query->getSingleScalarResult();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllQuery()
    {
        return $this->_em->createQuery("
          SELECT tags
          FROM {$this->_entityName} AS tags
          ORDER BY tags.id DESC
        ");
    }
}
